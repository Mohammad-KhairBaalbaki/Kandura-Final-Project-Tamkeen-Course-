<?php

namespace App\Providers;

use App\Models\Design;
use App\Models\Order;
use App\Models\User;
use App\Observers\DesignObserver;
use App\Observers\OrderObserver;
use App\Observers\UserObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
        Order::observe(OrderObserver::class);
        User::observe(UserObserver::class);
        Design::observe(DesignObserver::class);
    }
}
