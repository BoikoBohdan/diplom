<?php

namespace App\Policies\API\Admin;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the model.
     *
     * @param User $user
     * @param User $model
     * @return mixed
     */
    public function view (User $user, User $model)
    {
        //
    }

    /**
     * Determine whether the user can create models.
     *
     * @param User $user
     * @return mixed
     */
    public function create (User $user)
    {
        return $user->canCreateDriver();
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param User $user
     * @param User $model
     * @return mixed
     */
    public function update (User $user, User $model)
    {
        return $user->canUpdateUser($model);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param User $user
     * @param User $model
     * @return mixed
     */
    public function delete (User $user, User $model)
    {
        return $user->canDeleteUser($model);
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param User $user
     * @param User $model
     * @return mixed
     */
    public function restore (User $user, User $model)
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param User $user
     * @param User $model
     * @return mixed
     */
    public function forceDelete (User $user, User $model)
    {
        return false;
    }

    /**
     * @param User $user
     * @param $action
     * @return bool
     */
    public function before (User $user, $action)
    {
        $role = $user->role;

        if ($role->isMasterAdmin()) {
            return true;
        }

        if ($role->isDriver()) {
            return false;
        }
    }

}
