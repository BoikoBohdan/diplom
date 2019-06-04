<?php

namespace App\Events\API\Chat;


use App\Message;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct (Message $message)
    {
        $this->message = $message;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn ()
    {
        return new PresenceChannel('Chat.' . $this->message->chat_room_id);
    }

    /**
     * Get the channels the event should broadcast as.
     *
     * @return string
     */
    public function broadcastAs ()
    {
        return 'message.sent';
    }

    /**
     * Set data for broadcast
     *
     * @return array
     */
    public function broadcastWith ()
    {
        return [
            'id' => $this->message->id,
            'room_id' => $this->message->chat_room_id,
            'message' => $this->message->message,
            'type' => $this->message->type,
            'sender_id' => $this->message->sender_id,
            'created_at' => $this->message->created_at->format('Y-m-d H: i: s')
        ];
    }
}
