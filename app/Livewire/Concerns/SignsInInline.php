<?php

namespace App\Livewire\Concerns;

use App\Support\CustomerAuth;
use Livewire\Attributes\Locked;

/**
 * Inline "sign in to book" form on listing pages. After signing in the customer
 * lands back on the page they were viewing.
 */
trait SignsInInline
{
    public string $clientemail = '';

    public string $password = '';

    #[Locked]
    public string $returnTo = '/';

    public function mountSignsInInline(): void
    {
        $this->returnTo = request()->fullUrl();
    }

    public function signin()
    {
        $this->validate(
            ['clientemail' => 'required|email', 'password' => 'required|min:8'],
            [],
            ['clientemail' => 'email', 'password' => 'password'],
        );

        $auth = app(CustomerAuth::class);
        $result = $auth->attempt($this->clientemail, $this->password);

        if ($result === CustomerAuth::RESULT_OK) {
            session()->flash('alert', ['type' => 'success', 'message' => 'Welcome back, ' . $auth->name() . '!']);

            return $this->redirect($this->returnTo);
        }

        $this->reset('password');
        $this->addError('clientemail', $result === CustomerAuth::RESULT_BAD_PASSWORD
            ? 'That password is incorrect.'
            : 'No approved account exists for that email address.');
    }
}
