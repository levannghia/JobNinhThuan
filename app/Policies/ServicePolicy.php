<?php

namespace App\Policies;

use App\Models\Service;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ServicePolicy
{
    use HandlesAuthorization;


    public function viewAny(User $user)
    {
        //
    }

    public function push_news(User $user)
    {
        return $user->checkServiceAccess(config('permission_test.service_access.push_news'));
    }


    public function advanced_search(User $user)
    {
        return $user->checkServiceAccess(config('permission_test.service_access.advanced_search'));
    }

    public function filter_profile(User $user)
    {
        return $user->checkServiceAccess(config('permission_test.service_access.filter_profile'));
    }


    public function hot_recruitment(User $user)
    {
        return $user->checkServiceAccess(config('permission_test.service_access.hot_recruitment'));
    }


    public function urgent_recruitment(User $user)
    {
        return $user->checkServiceAccess(config('permission_test.service_access.urgent_recruitment'));
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user)
    {
        //
    }
}
