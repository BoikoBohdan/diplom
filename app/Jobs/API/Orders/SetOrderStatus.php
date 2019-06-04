<?php

namespace App\Jobs\API\Orders;

use App\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SetOrderStatus implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var Order
     */
    public $order;

    /**
     * @var int
     */
    public $status;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct (Order $order, int $status)
    {
        $this->order = $order;
        $this->status = $status;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle ()
    {
        $this->order->update(['status' => $this->status]);
    }
}
