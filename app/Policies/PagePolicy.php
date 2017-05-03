<?php

namespace App\Policies;

use App\User;
use App\Models\Page;
use Illuminate\Auth\Access\HandlesAuthorization;

class PagePolicy
{
    use HandlesAuthorization;

    public function all(User $user)
    {
        return $user->hasRole(config('security.permissions.page.view'));
    }

    /**
     * Determine whether the user can view the page.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Page  $page
     * @return mixed
     */
    public function view(User $user, Page $page)
    {
        return $user->hasRole(config('security.permissions.page.view'));
    }

    /**
     * Determine whether the user can create pages.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->hasRole(config('security.permissions.page.create'));
    }

    /**
     * Determine whether the user can update the page.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Page  $page
     * @return mixed
     */
    public function update(User $user, Page $page)
    {
        return $user->hasRole(config('security.permissions.page.edit'));
    }

    /**
     * Determine whether the user can delete the page.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Page  $page
     * @return mixed
     */
    public function delete(User $user, Page $page)
    {
        if (!$page->trashed())
        {
            return $user->hasRole(config('security.permissions.page.archive'));
        }

        return false;
    }
}
