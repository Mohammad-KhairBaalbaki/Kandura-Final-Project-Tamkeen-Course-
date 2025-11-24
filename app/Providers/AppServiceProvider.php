<?php

namespace App\Providers;

use App\Models\User;
use App\Policies\AdminPolicy;
use App\Policies\DesignOptionPolicy;
use App\Policies\DesignPolicy;
use App\Policies\UserPolicy;
use Illuminate\Support\Facades\Gate;
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
        Gate::policy(User::class, AdminPolicy::class);
        Gate::policy(User::class, DesignOptionPolicy::class);
        Gate::policy(User::class, DesignPolicy::class);
    }
}
