<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;

/** Create or update an admin backend account: php artisan panobooking:admin you@example.com --role=admin */
class MakeAdmin extends Command
{
    protected $signature = 'panobooking:admin {email : Sign-in email}
        {--name= : Display name (defaults to the part of the email before @)}
        {--role=admin : admin or staff}
        {--password= : Password (asked for when omitted; avoid passing it on shared machines)}';

    protected $description = 'Create or update an admin backend user (admin or staff role)';

    public function handle(): int
    {
        $email = strtolower(trim($this->argument('email')));
        $role = $this->option('role');
        $password = $this->option('password') ?? $this->secret('Password (min. 8 characters)');

        $validator = Validator::make(compact('email', 'role', 'password'), [
            'email' => 'required|email|max:190',
            'role' => 'required|in:' . implode(',', array_keys(User::ROLES)),
            'password' => 'required|string|min:8|max:190',
        ]);
        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $this->error($error);
            }

            return self::FAILURE;
        }

        $user = User::firstOrNew(['email' => $email]);
        $created = ! $user->exists;
        $user->name = $this->option('name') ?: ($user->name ?: ucfirst(strstr($email, '@', true)));
        $user->password = $password; // hashed by the model cast
        $user->role = $role;
        $user->save();

        $this->info(($created ? 'Created' : 'Updated') . " {$role} account {$email}. Sign in at " . url('/admin/login'));

        return self::SUCCESS;
    }
}
