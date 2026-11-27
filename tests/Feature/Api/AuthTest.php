<?php

namespace Tests\Feature\Api;

use App\Admin\RecordActions;
use App\Admin\Resources\CustomerResource;
use App\Mail\ResetPasswordCode;
use App\Mail\VerifyEmail;
use App\Models\User;
use App\Models\UserRegistrationModel;
use App\Support\CustomerAccounts;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class AuthTest extends ApiTestCase
{
    private function register(string $email = 'new@example.test')
    {
        return $this->postJson($this->api('auth/register'), [
            'name' => 'Nora New', 'email' => $email, 'phone_code' => '+256', 'phone' => '700 123456',
            'password' => 'secret-pass', 'password_confirmation' => 'secret-pass', 'currency' => 'USD',
        ]);
    }

    /** Register and capture the emailed code. */
    private function registerAndGetCode(string $email = 'new@example.test'): string
    {
        Mail::fake();
        $this->register($email)->assertCreated();
        $code = null;
        Mail::assertSent(VerifyEmail::class, function (VerifyEmail $mail) use ($email, &$code) {
            $code = $mail->code;

            return $mail->hasTo($email);
        });

        return $code;
    }

    public function test_register_verify_then_login_returns_a_token(): void
    {
        $code = $this->registerAndGetCode();
        $row = UserRegistrationModel::where('Email', 'new@example.test')->first();
        $this->assertSame('Not Approved', $row->ApprovalStatus);
        $this->assertSame('+256700123456', $row->Telephone);
        $this->assertSame('USD', $row->SelectedCurrency);
        $this->assertNotSame($code, $row->verification_code, 'the code is stored hashed');

        // Not verified yet: told to verify, no token.
        $this->postJson($this->api('auth/login'), ['email' => 'new@example.test', 'password' => 'secret-pass', 'device_name' => 'Pixel'])
            ->assertForbidden()->assertJson(['code' => 'unverified']);

        $this->postJson($this->api('auth/verify-email'), ['email' => 'new@example.test', 'code' => $code])
            ->assertOk()->assertJsonPath('data.email_verified', true);

        $token = $this->postJson($this->api('auth/login'), ['email' => 'new@example.test', 'password' => 'secret-pass', 'device_name' => 'Pixel'])
            ->assertOk()->assertJsonStructure(['token', 'token_type', 'data' => ['id', 'name', 'email', 'phone', 'currency']])
            ->json('token');

        $this->withToken($token)->getJson($this->api('me'))->assertOk()->assertJsonPath('data.email', 'new@example.test')
            ->assertJsonMissingPath('data.Password');
    }

    public function test_verifying_with_a_device_name_also_signs_in(): void
    {
        $code = $this->registerAndGetCode();

        $this->postJson($this->api('auth/verify-email'), ['email' => 'new@example.test', 'code' => $code, 'device_name' => 'iPhone'])
            ->assertOk()->assertJsonStructure(['token']);
    }

    public function test_wrong_and_expired_codes_are_rejected_and_lock_after_five_tries(): void
    {
        $code = $this->registerAndGetCode();
        $wrong = $code === '000000' ? '111111' : '000000';

        $this->postJson($this->api('auth/verify-email'), ['email' => 'new@example.test', 'code' => $wrong])
            ->assertUnprocessable()->assertJsonValidationErrors('code');

        DB::table('registration')->where('Email', 'new@example.test')->update(['verification_expires_at' => now()->subMinute()]);
        $this->postJson($this->api('auth/verify-email'), ['email' => 'new@example.test', 'code' => $code])
            ->assertUnprocessable()->assertJsonPath('errors.code.0', CustomerAccounts::message(CustomerAccounts::EXPIRED));

        DB::table('registration')->where('Email', 'new@example.test')->update(['verification_expires_at' => now()->addMinutes(10), 'code_attempts' => 5]);
        $this->postJson($this->api('auth/verify-email'), ['email' => 'new@example.test', 'code' => $code])
            ->assertUnprocessable()->assertJsonPath('errors.code.0', CustomerAccounts::message(CustomerAccounts::LOCKED));

        // A fresh code works again.
        Mail::fake();
        $this->postJson($this->api('auth/resend-verification'), ['email' => 'new@example.test'])->assertOk();
        Mail::assertSent(VerifyEmail::class, function ($mail) use (&$code) {
            $code = $mail->code;

            return true;
        });
        $this->postJson($this->api('auth/verify-email'), ['email' => 'new@example.test', 'code' => $code])->assertOk();
    }

    public function test_an_admin_approval_lets_the_customer_sign_in_without_the_email_step(): void
    {
        $this->registerAndGetCode();
        $id = UserRegistrationModel::where('Email', 'new@example.test')->value('id');

        $this->actingAs(User::where('email', 'staff@panobooking.test')->first());
        RecordActions::approve(CustomerResource::class, [$id], true);
        $this->assertNotNull(UserRegistrationModel::find($id)->email_verified_at);

        $this->postJson($this->api('auth/login'), ['email' => 'new@example.test', 'password' => 'secret-pass', 'device_name' => 'Pixel'])
            ->assertOk()->assertJsonStructure(['token']);
    }

    public function test_suspended_customers_lose_their_tokens(): void
    {
        $token = $this->demo()->createToken('Pixel')->plainTextToken;
        $this->withToken($token)->getJson($this->api('me'))->assertOk();

        $this->actingAs(User::where('email', 'admin@panobooking.test')->first());
        RecordActions::approve(CustomerResource::class, [$this->demo()->id], false);
        $this->app['auth']->forgetGuards();

        $this->withToken($token)->getJson($this->api('me'))->assertUnauthorized();
    }

    public function test_wrong_password_and_unknown_email_get_the_same_kind_of_error(): void
    {
        $this->postJson($this->api('auth/login'), ['email' => 'demo@panobooking.test', 'password' => 'nope-nope', 'device_name' => 'x'])
            ->assertUnprocessable()->assertJsonValidationErrors('email');
        $this->postJson($this->api('auth/login'), ['email' => 'nobody@example.test', 'password' => 'nope-nope', 'device_name' => 'x'])
            ->assertUnprocessable()->assertJsonValidationErrors('email');
        $this->postJson($this->api('auth/login'), [])->assertUnprocessable()->assertJsonValidationErrors(['email', 'password', 'device_name']);
    }

    public function test_registration_validates_and_rejects_duplicates(): void
    {
        $this->register('demo@panobooking.test')->assertUnprocessable()->assertJsonValidationErrors('email');
        $this->postJson($this->api('auth/register'), ['email' => 'x'])->assertUnprocessable()
            ->assertJsonValidationErrors(['name', 'email', 'phone_code', 'phone', 'password']);
    }

    public function test_login_is_throttled(): void
    {
        for ($i = 0; $i < 6; $i++) {
            $this->postJson($this->api('auth/login'), ['email' => 'demo@panobooking.test', 'password' => 'wrong-' . $i, 'device_name' => 'x']);
        }
        $this->postJson($this->api('auth/login'), ['email' => 'demo@panobooking.test', 'password' => 'password123', 'device_name' => 'x'])
            ->assertStatus(429);
    }

    public function test_logout_revokes_only_the_current_token_and_logout_everywhere_revokes_all(): void
    {
        $a = $this->demo()->createToken('phone')->plainTextToken;
        $b = $this->demo()->createToken('tablet')->plainTextToken;

        $this->withToken($a)->postJson($this->api('auth/logout'))->assertOk();
        $this->app['auth']->forgetGuards();
        $this->withToken($a)->getJson($this->api('me'))->assertUnauthorized();
        $this->app['auth']->forgetGuards();
        $this->withToken($b)->getJson($this->api('me'))->assertOk();

        $this->withToken($b)->deleteJson($this->api('me/tokens'))->assertOk();
        $this->assertSame(0, $this->demo()->tokens()->count());
    }

    public function test_password_reset_with_an_emailed_code(): void
    {
        Mail::fake();
        $this->postJson($this->api('auth/forgot-password'), ['email' => 'nobody@example.test'])->assertOk();
        Mail::assertNothingSent();

        $this->postJson($this->api('auth/forgot-password'), ['email' => 'demo@panobooking.test'])->assertOk();
        $code = null;
        Mail::assertSent(ResetPasswordCode::class, function ($mail) use (&$code) {
            $code = $mail->code;

            return $mail->hasTo('demo@panobooking.test');
        });
        $old = $this->demo()->createToken('old')->plainTextToken;

        $this->postJson($this->api('auth/reset-password'), ['email' => 'demo@panobooking.test', 'code' => $code,
            'password' => 'brand-new-pass', 'password_confirmation' => 'brand-new-pass'])->assertOk();

        $this->assertTrue(Hash::check('brand-new-pass', $this->demo()->Password));
        $this->assertSame(0, $this->demo()->tokens()->count(), 'every device is signed out');
        $this->postJson($this->api('auth/login'), ['email' => 'demo@panobooking.test', 'password' => 'brand-new-pass', 'device_name' => 'x'])->assertOk();
    }

    public function test_profile_update_and_password_change(): void
    {
        $this->signIn();

        $this->patchJson($this->api('me'), ['name' => 'Demo Renamed', 'currency' => 'EUR', 'phone' => '+256 701 000000'])
            ->assertOk()->assertJsonPath('data.name', 'Demo Renamed')->assertJsonPath('data.currency', 'EUR')
            ->assertJsonPath('data.phone', '+256701000000');
        $this->patchJson($this->api('me'), ['currency' => 'XYZ'])->assertUnprocessable();

        $this->putJson($this->api('me/password'), ['current_password' => 'wrong', 'password' => 'another-pass', 'password_confirmation' => 'another-pass'])
            ->assertUnprocessable()->assertJsonValidationErrors('current_password');
        $this->putJson($this->api('me/password'), ['current_password' => 'password123', 'password' => 'another-pass', 'password_confirmation' => 'another-pass'])
            ->assertOk();
        $this->assertTrue(Hash::check('another-pass', $this->demo()->Password));
    }

    public function test_signed_in_routes_need_a_token(): void
    {
        $this->getJson($this->api('me'))->assertUnauthorized()->assertJson(['message' => 'Unauthenticated.']);
        $this->getJson($this->api('bookings'))->assertUnauthorized();
        $this->postJson($this->api('bookings/room/1'))->assertUnauthorized();
    }
}
