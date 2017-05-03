<?php

namespace App\Observers;


use App\User;
use App\Models\Role;

class UserObserver
{
    public function registered(User $user)
    {
        $roleTypes = config('security.roles');

        $role = new Role();
        $role->type = $roleTypes[0];
        $user->roles()->save($role);
    }
}