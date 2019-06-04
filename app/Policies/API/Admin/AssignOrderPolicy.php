<?php

namespace App\Policies\API\Admin;

use App\DriverOrder;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AssignOrderPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the driver order.
     *
     * @param User $user
     * @param DriverOrder $driverOrder
     * @return mixed
     */
    public function view (User $user, DriverOrder $driverOrder)
    {
        //
    }

    /**
     * Determine whether the user can create driver orders.
     *
     * @param User $user
     * @return mixed
     */
    public function create (User $user)
    {
        return $user->canAssignOrder();
    }

    /**
     * Determine whether the user can update the driver order.
     *
     * @param User $user
     * @param DriverOrder $driverOrder
     * @return mixed
     */
    public function update (User $user, DriverOrder $driverOrder)
    {
        //
    }

    /**
     * Determine whether the user can delete the driver order.
     *
     * @param User $user
     * @param DriverOrder $driverOrder
     * @return mixed
     */
    public function delete (User $user, DriverOrder $driverOrder)
    {
        //
    }

    /**
     * Determine whether the user can restore the driver order.
     *
     * @param User $user
     * @param DriverOrder $driverOrder
     * @return mixed
     */
    public function restore (User $user, DriverOrder $driverOrder)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the driver order.
     *
     * @param User $user
     * @param DriverOrder $driverOrder
     * @return mixed
     */
    public function forceDelete (User $user, DriverOrder $driverOrder)
    {
        //
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
