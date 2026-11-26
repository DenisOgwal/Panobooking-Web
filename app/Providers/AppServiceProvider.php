<?php

namespace App\Providers;

use App\Support\Currency;
use App\Support\CustomerAuth;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

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
    }
}
