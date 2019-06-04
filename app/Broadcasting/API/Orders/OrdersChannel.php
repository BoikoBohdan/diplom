<?php

namespace App\Broadcasting\API\Orders;

use App\User;

class OrdersChannel
{
    /**
     * Create a new channel instance.
     *
     * @return void
     */
    public function __construct ()
    {
        //
    }

    /**
     * Authenticate the user's access to the channel.
     *
     * @param User $user
     * @return array|bool
     */
    public function join ()
    {
        return true;
    }
}
