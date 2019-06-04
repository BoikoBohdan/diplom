<?php

namespace App\Broadcasting\API\Chat;

use App\User;

class ChatRoomChannel
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
    public function join (User $user, $room)
    {
        return $user->canJoinRoom($room)
            ? [
                'id' => $user->id,
                'full_name' => $user->full_name
            ]
            : false;
    }
}
