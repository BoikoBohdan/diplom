<?php

namespace App\Events\API\Orders;

use App\Http\Resources\GodsEye\Orders\OrdersResource;
use App\Order;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class GodsEyeOrder implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Order
     *
     * @var Order
     */
    public $order;

    /**
     * Method
     *
     * @var string
     */
    public $method;

    /**
     * Create a new event instance.
     *
     * @param Order $order
     * @param string $method
     * @return void
     */
    public function __construct (Order $order, string $method)
    {
        $this->order = $order;
        $this->method = $method;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn ()
    {
        return 'GodsEye';
    }

    /**
     * Get the channels the event should broadcast as.
     *
     * @return Channel|array
     */
    public function broadcastAs ()
    {
        return 'GodsEyeOrder';
    }

    /**
     * Get the channels the event should broadcast as.
     *
     * @return Channel|array
     */
    public function broadcastWith ()
    {
        return [
            'order' => new OrdersResource($this->order),
            'method' => $this->method
        ];
    }
}
