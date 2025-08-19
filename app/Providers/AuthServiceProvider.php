<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Gate::define('be-an-admin', fn(User $user) =>$user->hasPermissionTo('be an admin'));
        Gate::define('be-a-manager', fn(User $user) =>$user->hasPermissionTo('be a manager'));
        Gate::define('be-an-user', fn(User $user) =>$user->hasPermissionTo('be an user'));
    }
}
