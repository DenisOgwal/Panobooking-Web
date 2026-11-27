<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [];

    public function boot(): void
    {
        $this->registerPolicies();

        // Admin backend: staff and admins can view, create, edit, approve and confirm bookings…
        Gate::define('admin.access', fn (User $user) => $user->canAccessAdmin());
        // …only admins can delete records and manage admin users and currencies.
        Gate::define('admin.manage', fn (User $user) => $user->isAdmin());
        // …and only developers can use the system tools (database migrations).
        Gate::define('admin.develop', fn (User $user) => $user->isDeveloper());
    }
}
