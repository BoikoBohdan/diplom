<?php

namespace App\Events\API\Orders;

use App\Http\Resources\Orders\Index;
use App\Order;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ActionOnOrder implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $order;
    public $action;

    /**
     * Create a new event instance.
     *
     * @param Order $order
     * @param string $action
     */
    public function __construct (Order $order, string $action)
    {
        $this->order = $order;
        $this->action = $action;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return string
     */
    public function broadcastOn ()
    {
        return 'Orders';
    }

    /**
     * Get the channels the event should broadcast as.
     *
     * @return string
     */
    public function broadcastAs (): string
    {
        return 'action-on-order';
    }

    /**
     * Get the channels the event should broadcast as.
     *
     * @return Channel|array
     */
    public function broadcastWith ()
    {
        return [
            'order' => new Index($this->order),
            'action' => $this->action
        ];
    }
}
