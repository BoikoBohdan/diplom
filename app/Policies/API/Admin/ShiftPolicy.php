<?php

namespace App\Policies\API\Admin;

use App\Shift;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ShiftPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the shift.
     *
     * @param User $user
     * @param Shift $shift
     * @return mixed
     */
    public function view (User $user, Shift $shift)
    {
        return true;
    }

    /**
     * Determine whether the user can create shifts.
     *
     * @param User $user
     * @return mixed
     */
    public function create (User $user)
    {
        return $user->canCreateShift();
    }

    /**
     * Determine whether the user can update the shift.
     *
     * @param User $user
     * @param Shift $shift
     * @return mixed
     */
    public function update (User $user, Shift $shift)
    {
        return $user->canUpdateShift();
    }

    /**
     * Determine whether the user can delete the shift.
     *
     * @param User $user
     * @param Shift $shift
     * @return mixed
     */
    public function delete (User $user, Shift $shift)
    {
        return $user->canDeleteShift();
    }

    /**
     * Determine whether the user can restore the shift.
     *
     * @param User $user
     * @param Shift $shift
     * @return mixed
     */
    public function restore (User $user, Shift $shift)
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the shift.
     *
     * @param User $user
     * @param Shift $shift
     * @return mixed
     */
    public function forceDelete (User $user, Shift $shift)
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
