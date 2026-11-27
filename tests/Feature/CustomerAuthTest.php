<?php

namespace Tests\Feature;

use App\Support\CustomerAuth;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class CustomerAuthTest extends TestCase
{
    use RefreshDatabase;

    /** Seed once when the test database is migrated; each test then runs in a rolled-back transaction. */
    protected $seed = true;

    private function account(string $password, string $status = 'Approved', string $email = 'amina@example.test'): void
    {
        DB::table('registration')->insert([
            'FullNames' => 'Amina Test', 'Email' => $email, 'Password' => $password,
            'Telephone' => '+256700000009', 'ApprovalStatus' => $status, 'SelectedCurrency' => 'UGX',
        ]);
    }

    private function storedHash(string $email = 'amina@example.test'): string
    {
        return DB::table('registration')->where('Email', $email)->value('Password');
    }

    public function test_legacy_md5_login_works_and_is_rehashed(): void
    {
        config(['panobooking.password_upgrade' => true]);
        $this->account(md5('secret-pass'));

        $this->assertSame(CustomerAuth::RESULT_OK, app(CustomerAuth::class)->attempt('amina@example.test', 'secret-pass'));
        $this->assertFalse(CustomerAuth::isLegacyMd5($this->storedHash()));
        $this->assertTrue(Hash::check('secret-pass', $this->storedHash()));

        // and the upgraded hash still logs in
        $this->assertSame(CustomerAuth::RESULT_OK, app(CustomerAuth::class)->attempt('amina@example.test', 'secret-pass'));
    }

    public function test_md5_is_kept_when_upgrade_is_disabled(): void
    {
        config(['panobooking.password_upgrade' => false]);
        $this->account(md5('secret-pass'));

        $this->assertSame(CustomerAuth::RESULT_OK, app(CustomerAuth::class)->attempt('amina@example.test', 'secret-pass'));
        $this->assertSame(md5('secret-pass'), $this->storedHash());
        $this->assertSame(md5('x'), CustomerAuth::hash('x'));
    }

    public function test_wrong_password_and_unapproved_accounts_are_rejected(): void
    {
        $this->account(md5('secret-pass'));
        $this->account(md5('secret-pass'), 'Not Approved', 'pending@example.test');

        $auth = app(CustomerAuth::class);
        $this->assertSame(CustomerAuth::RESULT_BAD_PASSWORD, $auth->attempt('amina@example.test', 'wrong-pass'));
        // Right password but email never verified: tell them to verify. Wrong password: reveal nothing.
        $this->assertSame(CustomerAuth::RESULT_UNVERIFIED, $auth->attempt('pending@example.test', 'secret-pass'));
        $this->assertSame(CustomerAuth::RESULT_UNKNOWN, $auth->attempt('pending@example.test', 'wrong-pass'));
        $this->assertSame(CustomerAuth::RESULT_UNKNOWN, $auth->attempt('nobody@example.test', 'secret-pass'));
        $this->assertFalse($auth->check());

        // Verified, then suspended by an admin.
        DB::table('registration')->where('Email', 'pending@example.test')->update(['email_verified_at' => now()]);
        $this->assertSame(CustomerAuth::RESULT_UNKNOWN, $auth->attempt('pending@example.test', 'secret-pass'));
    }

    public function test_session_holds_no_password(): void
    {
        $this->account(Hash::make('secret-pass'));

        app(CustomerAuth::class)->attempt('amina@example.test', 'secret-pass');

        $this->assertSame(['Email' => 'amina@example.test', 'FullNames' => 'Amina Test', 'Telephone' => '+256700000009'],
            session(CustomerAuth::SESSION_KEY));
    }

    public function test_logout_route_clears_the_customer(): void
    {
        $this->withSession([CustomerAuth::SESSION_KEY => ['Email' => 'a@b.test', 'FullNames' => 'A']])
            ->get('/clientlogout')
            ->assertRedirect('/')
            ->assertSessionMissing(CustomerAuth::SESSION_KEY);
    }
}
