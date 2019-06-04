<?php

namespace App\Events\API\Chat;

use App\ChatRoom;
use App\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class RoomCreated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user;
    public $room;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct (User $user, ChatRoom $room)
    {
        $this->user = $user;
        $this->room = $room;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn ()
    {
        return new PrivateChannel('User.' . $this->user->id);
    }

    /**
     * Get the channels the event should broadcast as.
     *
     * @return Channel|array
     */
    public function broadcastAs ()
    {
        return 'room.created';
    }

    /**
     * Get the channels the event should broadcast as.
     *
     * @return Channel|array
     */
    public function broadcastWith ()
    {
        return [
            'chat_room_id' => $this->room->id,
        ];
    }
}
