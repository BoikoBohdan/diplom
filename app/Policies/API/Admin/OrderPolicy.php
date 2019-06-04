<?php

namespace App\Policies\API\Admin;

use App\Order;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class OrderPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the order.
     *
     * @param User $user
     * @param Order $order
     * @return mixed
     */
    public function view (User $user, Order $order)
    {
        //
    }

    /**
     * Determine whether the user can create orders.
     *
     * @param User $user
     * @return mixed
     */
    public function create (User $user)
    {
        return $user->canCreateOrder();
    }

    /**
     * Determine whether the user can update the order.
     *
     * @param User $user
     * @param Order $order
     * @return mixed
     */
    public function update (User $user, Order $order)
    {
        return $user->canUpdateOrder();
    }

    /**
     * Determine whether the user can delete the order.
     *
     * @param User $user
     * @param Order $order
     * @return bool
     */
    public function delete (User $user, Order $order)
    {
        return $user->canDeleteOrder();
    }

    /**
     * Determine whether the user can restore the order.
     *
     * @param User $user
     * @param Order $order
     * @return mixed
     */
    public function restore (User $user, Order $order)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the order.
     *
     * @param User $user
     * @param Order $order
     * @return mixed
     */
    public function forceDelete (User $user, Order $order)
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

        if ($role->isDriver()) {
            return false;
        }

        if ($action != 'delete') {
            if ($role->isMasterAdmin()) {
                return true;
            }
        }
    }
}
