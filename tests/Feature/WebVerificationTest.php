<?php

namespace Tests\Feature;

use App\Livewire\ForgotPasswordPage;
use App\Livewire\LoginPage;
use App\Livewire\RegisterPage;
use App\Livewire\VerifyEmailPage;
use App\Mail\ResetPasswordCode;
use App\Mail\VerifyEmail;
use App\Models\UserRegistrationModel;
use App\Support\CustomerAuth;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Livewire\Livewire;
use Tests\TestCase;

class WebVerificationTest extends TestCase
{
    use RefreshDatabase;

    protected $seed = true;

    private function signUp(): string
    {
        Mail::fake();
        Livewire::test(RegisterPage::class)
            ->set('name', 'Web Person')->set('email', 'web@example.test')->set('phonenum', '700123456')
            ->set('password', 'secret-pass')->set('confirmpassword', 'secret-pass')
            ->call('submit')->assertSet('registered', true);

        $code = null;
        Mail::assertSent(VerifyEmail::class, function ($mail) use (&$code) {
            $code = $mail->code;

            return $mail->hasTo('web@example.test');
        });

        return $code;
    }

    public function test_sign_up_then_verify_with_the_code_then_sign_in(): void
    {
        $code = $this->signUp();

        Livewire::test(LoginPage::class)->set('email', 'web@example.test')->set('password', 'secret-pass')
            ->call('signin')->assertHasErrors('email')->assertSet('unverified', true);

        Livewire::test(VerifyEmailPage::class)
            ->assertSet('email', 'web@example.test') // remembered from sign-up, not passed in the URL
            ->set('code', '12345')->call('verify')->assertHasErrors('code')
            ->set('code', $code)->call('verify')->assertHasNoErrors()->assertRedirect('/Login');

        $this->assertSame(CustomerAuth::RESULT_OK, app(CustomerAuth::class)->attempt('web@example.test', 'secret-pass'));
    }

    public function test_the_signed_link_verifies_and_a_tampered_one_does_not(): void
    {
        $this->signUp();
        $link = null;
        Mail::assertSent(VerifyEmail::class, function ($mail) use (&$link) {
            $link = $mail->link;

            return true;
        });

        $this->get($link . 'x')->assertForbidden();
        $this->assertFalse(UserRegistrationModel::where('Email', 'web@example.test')->first()->isVerified());

        $this->get($link)->assertRedirect('/Login');
        $this->assertTrue(UserRegistrationModel::where('Email', 'web@example.test')->first()->isApproved());
    }

    public function test_forgot_password_on_the_website(): void
    {
        Mail::fake();
        $this->get('/ForgotPassword')->assertOk();

        $page = Livewire::test(ForgotPasswordPage::class)->set('email', 'demo@panobooking.test')->call('sendCode')->assertSet('codeSent', true);
        $code = null;
        Mail::assertSent(ResetPasswordCode::class, function ($mail) use (&$code) {
            $code = $mail->code;

            return true;
        });

        $page->set('code', $code)->set('password', 'new-web-pass')->set('password_confirmation', 'new-web-pass')
            ->call('resetPassword')->assertHasNoErrors()->assertRedirect('/Login');

        $this->assertTrue(Hash::check('new-web-pass', UserRegistrationModel::where('Email', 'demo@panobooking.test')->value('Password')));
    }

    public function test_pages_render(): void
    {
        $this->get('/VerifyEmail')->assertOk()->assertSee('Verify your email');
        $this->get('/Login')->assertOk()->assertSee('Forgot your password?');
        $this->get('/api/docs')->assertOk()->assertSee('swagger-ui');
        $this->get('/api/openapi.yaml')->assertOk();
    }
}
