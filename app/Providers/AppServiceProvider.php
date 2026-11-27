<?php

namespace App\Providers;

use App\Http\Middleware\EnsureAdmin;
use App\Support\Currency;
use App\Support\CustomerAuth;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Schema;
use App\View\Composers\FooterComposer;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // One instance per request (Livewire update requests included).
        $this->app->scoped(Currency::class);
        $this->app->scoped(CustomerAuth::class);
    }

    public function boot(): void
    {
        Schema::defaultStringLength(191);
        Paginator::useBootstrapFive();

        View::composer('components.site-footer', FooterComposer::class);

        // Re-run the admin gate on Livewire update requests of admin pages, not just the first load.
        Livewire::addPersistentMiddleware([EnsureAdmin::class]);
    }
}
