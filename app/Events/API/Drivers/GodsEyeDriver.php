<?php

namespace App\Events\API\Drivers;

use App\Driver;
use App\Http\Resources\GodsEye\Drivers\DriversResource;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class GodsEyeDriver implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $driver;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct (Driver $driver)
    {
        $this->driver = $driver;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return string
     */
    public function broadcastOn ()
    {
        return 'GodsEye';
    }

    /**
     * Get the channels the event should broadcast as.
     *
     * @return string
     */
    public function broadcastAs ()
    {
        return 'action-on-driver';
    }

    /**
     * Get the channels the event should broadcast with.
     *
     * @return array
     */
    public function broadcastWith ()
    {
        return [
            'driver' => new DriversResource($this->driver)
        ];
    }
}
