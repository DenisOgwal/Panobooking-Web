<?php

namespace App\Livewire;

use App\Support\CustomerAccounts;
use Illuminate\Support\Facades\RateLimiter;
use Livewire\Component;

/** /VerifyEmail: enter the 6-digit code from the sign-up email, or ask for a new one. */
class VerifyEmailPage extends Component
{
    /** Session key holding the email just registered (so it is never put in the URL). */
    public const SESSION_KEY = 'verify_email';

    public string $email = '';
    public string $code = '';

    public function mount(): void
    {
        $this->email = (string) session(self::SESSION_KEY, '');
    }

    public function verify()
    {
        $this->validate(['email' => 'required|email', 'code' => 'required|digits:6'], [], ['code' => 'code']);
        if ($this->tooMany('verify')) {
            return null;
        }

        $result = app(CustomerAccounts::class)->verify($this->email, $this->code);
        if ($result !== CustomerAccounts::OK) {
            $this->addError('code', CustomerAccounts::message($result));

            return null;
        }

        session()->forget(self::SESSION_KEY);
        session()->flash('alert', ['type' => 'success', 'message' => 'Email verified. You can sign in now.']);

        return $this->redirect('/Login');
    }

    public function resend(): void
    {
        $this->validate(['email' => 'required|email']);
        if ($this->tooMany('resend')) {
            return;
        }

        $account = \App\Models\UserRegistrationModel::where('Email', $this->email)->first();
        if ($account) {
            app(CustomerAccounts::class)->sendVerification($account);
        }
        // Same message either way, so the page never reveals who has an account.
        $this->dispatch('alert', type: 'success', message: 'If that address has an unverified account, a new code is on its way.');
    }

    private function tooMany(string $action): bool
    {
        $key = "verify-email:{$action}:" . strtolower($this->email) . '|' . request()->ip();
        if (RateLimiter::tooManyAttempts($key, 6)) {
            $this->addError($action === 'verify' ? 'code' : 'email', 'Too many attempts. Try again in a minute.');

            return true;
        }
        RateLimiter::hit($key, 60);

        return false;
    }

    public function render()
    {
        return view('livewire.verify-email')->layout('components.layouts.app', ['title' => 'Verify your email']);
    }
}
