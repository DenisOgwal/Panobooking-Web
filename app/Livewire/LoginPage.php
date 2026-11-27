<?php

namespace App\Livewire;

use App\Support\CustomerAuth;
use Livewire\Attributes\Url;
use Livewire\Attributes\Validate;
use Livewire\Component;

/** /Login */
class LoginPage extends Component
{
    #[Validate('required|email')]
    public string $email = '';

    #[Validate('required|min:8')]
    public string $password = '';

    /** Optional local path to return to after signing in (?return=/PendingBookings). */
    #[Url(as: 'return', except: '')]
    public string $returnTo = '';

    /** The last attempt had the right password but an unverified email. */
    public bool $unverified = false;

    public function mount()
    {
        if (app(CustomerAuth::class)->check()) {
            return redirect()->to($this->safeReturn());
        }
    }

    public function signin()
    {
        $this->validate();

        $auth = app(CustomerAuth::class);
        $result = $auth->attempt($this->email, $this->password);

        if ($result === CustomerAuth::RESULT_OK) {
            session()->flash('alert', ['type' => 'success', 'message' => 'Welcome back, ' . $auth->name() . '!']);

            return $this->redirect($this->safeReturn());
        }

        $this->reset('password');
        if ($result === CustomerAuth::RESULT_UNVERIFIED) {
            session()->put(VerifyEmailPage::SESSION_KEY, $this->email);
        }
        $this->unverified = $result === CustomerAuth::RESULT_UNVERIFIED;
        $this->addError('email', CustomerAuth::message($result));
    }

    /** Only allow local paths, never another host. */
    private function safeReturn(): string
    {
        $to = $this->returnTo;

        return (str_starts_with($to, '/') && ! str_starts_with($to, '//')) ? $to : '/';
    }

    public function render()
    {
        return view('livewire.login-page')->layout('components.layouts.app', ['title' => 'Sign in']);
    }
}
