<?php

namespace App\Broadcasting\API\Chat;

use App\User;

class UserChannel
{
    /**
     * Create a new channel instance.
     *
     * @return void
     */
    public function __construct (User $user)
    {
        //
    }

    /**
     * Authenticate the user's access to the channel.
     *
     * @param User $user
     * @return array|bool
     */
    public function join (User $user, int $id)
    {
        return $user->id === $id;
    }
}
