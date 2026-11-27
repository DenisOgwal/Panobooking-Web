<?php

namespace App\Livewire;

use App\Support\CustomerAccounts;
use Illuminate\Support\Facades\RateLimiter;
use Livewire\Component;

/** /ForgotPassword: email a 6-digit code, then set a new password with it. */
class ForgotPasswordPage extends Component
{
    public string $email = '';
    public string $code = '';
    public string $password = '';
    public string $password_confirmation = '';

    public bool $codeSent = false;

    public function sendCode(): void
    {
        $this->validate(['email' => 'required|email']);
        if ($this->tooMany('send')) {
            return;
        }

        app(CustomerAccounts::class)->sendPasswordReset($this->email);
        $this->codeSent = true;
    }

    public function resetPassword()
    {
        $this->validate([
            'email' => 'required|email',
            'code' => 'required|digits:6',
            'password' => 'required|string|min:8|max:100|confirmed',
        ], [], ['code' => 'code']);
        if ($this->tooMany('reset')) {
            return null;
        }

        $result = app(CustomerAccounts::class)->resetPassword($this->email, $this->code, $this->password);
        $this->reset('password', 'password_confirmation');
        if ($result !== CustomerAccounts::OK) {
            $this->addError('code', CustomerAccounts::message($result));

            return null;
        }

        session()->flash('alert', ['type' => 'success', 'message' => 'Password changed. Sign in with your new password.']);

        return $this->redirect('/Login');
    }

    private function tooMany(string $action): bool
    {
        $key = "forgot-password:{$action}:" . strtolower($this->email) . '|' . request()->ip();
        if (RateLimiter::tooManyAttempts($key, 6)) {
            $this->addError($action === 'send' ? 'email' : 'code', 'Too many attempts. Try again in a minute.');

            return true;
        }
        RateLimiter::hit($key, 60);

        return false;
    }

    public function render()
    {
        return view('livewire.forgot-password')->layout('components.layouts.app', ['title' => 'Reset your password']);
    }
}
