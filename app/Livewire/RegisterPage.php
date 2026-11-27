<?php

namespace App\Livewire;

use App\Support\CustomerAccounts;
use Livewire\Component;

/**
 * /Register: new accounts start "Not Approved" and are approved when the customer verifies
 * their email (code or link, see /VerifyEmail) or when an admin approves them.
 */
class RegisterPage extends Component
{
    public string $name = '';
    public string $email = '';
    public string $code = '+256';
    public string $phonenum = '';
    public string $password = '';
    public string $confirmpassword = '';

    public bool $registered = false;

    protected function rules(): array
    {
        return [
            'name' => CustomerAccounts::RULES['name'],
            'email' => CustomerAccounts::RULES['email'],
            'code' => CustomerAccounts::RULES['phone_code'],
            'phonenum' => CustomerAccounts::RULES['phone'],
            'password' => CustomerAccounts::RULES['password'],
            'confirmpassword' => 'required|same:password',
        ];
    }

    protected function messages(): array
    {
        return [
            'email.unique' => CustomerAccounts::MESSAGES['email.unique'],
            'confirmpassword.same' => 'The passwords do not match.',
            'phonenum.regex' => CustomerAccounts::MESSAGES['phone.regex'],
        ];
    }

    protected function validationAttributes(): array
    {
        return ['name' => 'full name', 'code' => 'country code', 'phonenum' => 'phone number', 'confirmpassword' => 'password confirmation'];
    }

    public function updated($field): void
    {
        if ($field !== 'password') {
            $this->validateOnly($field);
        }
    }

    public function submit(): void
    {
        $this->validate();

        app(CustomerAccounts::class)->register([
            'name' => $this->name,
            'email' => $this->email,
            'telephone' => CustomerAccounts::phone($this->code, $this->phonenum),
            'password' => $this->password,
        ]);
        session()->put(VerifyEmailPage::SESSION_KEY, $this->email);

        $this->reset('password', 'confirmpassword');
        $this->registered = true;
    }

    public function render()
    {
        return view('livewire.register-page')->layout('components.layouts.app', ['title' => 'Create an account']);
    }
}
