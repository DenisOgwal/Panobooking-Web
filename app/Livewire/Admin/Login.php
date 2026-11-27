<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

/** /admin/login: sign-in for admin and staff accounts (`users` table). */
class Login extends Component
{
    public string $email = '';
    public string $password = '';
    public bool $remember = false;

    private const MAX_ATTEMPTS = 5;

    public function mount(): void
    {
        if (Auth::user()?->canAccessAdmin()) {
            $this->redirectRoute('admin.dashboard');
        }
    }

    public function login(): void
    {
        $this->validate(['email' => 'required|email|max:190', 'password' => 'required|string|max:190']);

        $key = 'admin-login:' . Str::lower($this->email) . '|' . request()->ip();
        if (RateLimiter::tooManyAttempts($key, self::MAX_ATTEMPTS)) {
            throw ValidationException::withMessages([
                'email' => 'Too many attempts. Try again in ' . RateLimiter::availableIn($key) . ' seconds.',
            ]);
        }

        $credentials = [
            'email' => $this->email,
            'password' => $this->password,
            fn ($query) => $query->whereIn('role', array_keys(User::ROLES)),
        ];
        if (! Auth::attempt($credentials, $this->remember)) {
            RateLimiter::hit($key, 60);
            $this->reset('password');
            throw ValidationException::withMessages(['email' => 'These details do not match an admin account.']);
        }

        RateLimiter::clear($key);
        session()->regenerate();
        $this->redirectIntended(route('admin.dashboard'));
    }

    public function render()
    {
        return view('livewire.admin.login')->layout('components.layouts.admin-guest', ['title' => 'Sign in']);
    }
}
