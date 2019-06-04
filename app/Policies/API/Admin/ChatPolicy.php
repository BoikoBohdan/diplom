<?php

namespace App\Policies\API\Admin;

use App\ChatRoom;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ChatPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the chat room.
     *
     * @param User $user
     * @param ChatRoom $chatRoom
     * @return mixed
     */
    public function view (User $user, ChatRoom $chatRoom)
    {
        return $user->canViewChat();
    }

    /**
     * Determine whether the user can create chat rooms.
     *
     * @param User $user
     * @return mixed
     */
    public function create (User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the chat room.
     *
     * @param User $user
     * @param ChatRoom $chatRoom
     * @return mixed
     */
    public function update (User $user, ChatRoom $chatRoom)
    {
        //
    }

    /**
     * Determine whether the user can delete the chat room.
     *
     * @param User $user
     * @param ChatRoom $chatRoom
     * @return mixed
     */
    public function delete (User $user, ChatRoom $chatRoom)
    {
        //
    }

    /**
     * Determine whether the user can restore the chat room.
     *
     * @param User $user
     * @param ChatRoom $chatRoom
     * @return mixed
     */
    public function restore (User $user, ChatRoom $chatRoom)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the chat room.
     *
     * @param User $user
     * @param ChatRoom $chatRoom
     * @return mixed
     */
    public function forceDelete (User $user, ChatRoom $chatRoom)
    {
        //
    }
}
