<?php

namespace App\Observers;


use App\User;
use App\Models\Role;

class UserObserver
{
    public function registered(User $user)
    {
        $role = new Role();
        $role->type = 'new';
        $user->roles()->save($role);
    }
}