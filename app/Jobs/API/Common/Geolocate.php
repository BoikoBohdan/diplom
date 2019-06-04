<?php

namespace App\Jobs\API\Common;

use Geocoder;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class Geolocate implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Address of location
     *
     * @var string
     */
    public $location;

    /**
     * Create a new job instance.
     *
     * @param string $location
     * @param Model $model
     * @return void
     */
    public function __construct (string $location)
    {
        $this->location = $location;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle ()
    {
        return Geocoder::getCoordinatesForAddress($this->location);
    }
}
