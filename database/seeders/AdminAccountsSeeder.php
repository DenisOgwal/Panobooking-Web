<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

/**
 * The two accounts every deployment needs: an administrator and a developer.
 * Runs on every container start (docker/entrypoint.sh) and is safe to repeat:
 *
 * - credentials come from config('panobooking.accounts'), i.e. ADMIN_EMAIL / ADMIN_PASSWORD and
 *   DEVELOPER_EMAIL / DEVELOPER_PASSWORD (plus optional ADMIN_NAME / DEVELOPER_NAME);
 * - an account is created only if its email doesn't exist yet. An existing account keeps its
 *   password (changed passwords survive redeploys) and just gets its role back if it was lost;
 * - an account without both values set is skipped with a warning, never created with a default.
 *
 * Change a password later with `php artisan panobooking:admin {email} --role=…`.
 */
class AdminAccountsSeeder extends Seeder
{
    public function run(): void
    {
        if (! Schema::hasTable('users') || ! Schema::hasColumn('users', 'role')) {
            $this->command?->warn('Admin accounts skipped: run the migrations first.');

            return;
        }

        $this->account('admin', User::ROLE_ADMIN, 'ADMIN');
        $this->account('developer', User::ROLE_DEVELOPER, 'DEVELOPER');
    }

    private function account(string $key, string $role, string $envPrefix): void
    {
        $config = config("panobooking.accounts.{$key}", []);
        $label = $config['name'] ?? ucfirst($key);
        $email = strtolower(trim((string) ($config['email'] ?? '')));
        $password = (string) ($config['password'] ?? '');

        if ($email === '' || $password === '') {
            $this->command?->warn("{$label} account skipped: set {$envPrefix}_EMAIL and {$envPrefix}_PASSWORD.");

            return;
        }
        if (! filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($password) < 8) {
            $this->command?->error("{$label} account skipped: {$envPrefix}_EMAIL must be an email and {$envPrefix}_PASSWORD at least 8 characters.");

            return;
        }

        $user = User::where('email', $email)->first();
        if (! $user) {
            (new User)->forceFill([
                'name' => $label,
                'email' => $email,
                'password' => $password, // hashed by the model cast
                'role' => $role,
            ])->save();
            $this->command?->info("{$label} account created: {$email}");

            return;
        }

        if ($user->role !== $role) {
            $user->forceFill(['role' => $role])->save();
            $this->command?->info("{$label} account {$email}: role set to {$role}.");
        }
    }
}
