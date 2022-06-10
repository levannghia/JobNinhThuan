<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('view-role', [RolePolicy::class, 'view']);
        Gate::define('edit-role', [RolePolicy::class, 'edit']);
        Gate::define('add-role', [RolePolicy::class, 'add']);
        Gate::define('delete-role', [RolePolicy::class, 'delete']);
    }
}
