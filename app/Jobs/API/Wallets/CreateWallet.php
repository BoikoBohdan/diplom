<?php

namespace App\Jobs\API\Wallets;

use App\Driver;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CreateWallet implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var Driver
     */
    public $driver;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct (Driver $driver)
    {
        $this->driver = $driver;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle ()
    {
        $this->driver->wallet()->create();
    }
}
