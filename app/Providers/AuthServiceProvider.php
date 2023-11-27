<?php

namespace App\Providers;

use App\Models\Service;
use App\Policies\ServicePolicy;
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
        //Service::class => ServicePolicy::class,
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

        //service
        Gate::define('push_news', [ServicePolicy::class, 'push_news']);
        Gate::define('advanced_search', [ServicePolicy::class, 'advanced_search']);
        Gate::define('filter_profile', [ServicePolicy::class, 'filter_profile']);
        Gate::define('hot_recruitment', [ServicePolicy::class, 'hot_recruitment']);
        Gate::define('urgent_recruitment', [ServicePolicy::class, 'urgent_recruitment']);
    }
}
