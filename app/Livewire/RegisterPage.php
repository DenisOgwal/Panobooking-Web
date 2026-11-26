<?php

namespace App\Livewire;

use App\Models\UserRegistrationModel;
use App\Support\CustomerAuth;
use App\Support\ProviderNotifier;
use Livewire\Component;

/** /Register: new customer accounts start as "Not Approved" until an admin approves them. */
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
            'name' => 'required|string|min:3|max:120',
            'email' => 'required|email|max:190|unique:registration,Email',
            'code' => ['required', 'regex:/^\+\d{1,4}$/'],
            'phonenum' => ['required', 'regex:/^[0-9 ]{6,15}$/'],
            'password' => 'required|string|min:8|max:100',
            'confirmpassword' => 'required|same:password',
        ];
    }

    protected function messages(): array
    {
        return [
            'email.unique' => 'An account with this email already exists. Try signing in instead.',
            'confirmpassword.same' => 'The passwords do not match.',
            'phonenum.regex' => 'Enter digits only, e.g. 700 123456.',
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

        UserRegistrationModel::create([
            'FullNames' => $this->name,
            'Email' => $this->email,
            'Password' => CustomerAuth::hash($this->password),
            'Telephone' => $this->code . preg_replace('/\s+/', '', $this->phonenum),
            'ApprovalStatus' => 'Not Approved',
        ]);

        app(ProviderNotifier::class)->send('checkreg.php', ['emails' => $this->email]);

        $this->reset('password', 'confirmpassword');
        $this->registered = true;
    }

    public function render()
    {
        return view('livewire.register-page')->layout('components.layouts.app', ['title' => 'Create an account']);
    }
}
