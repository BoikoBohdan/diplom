<?php

namespace App;

use Illuminate\{Database\Eloquent\Model, Support\Collection};
use Spatie\Activitylog\Traits\LogsActivity;

class Wallet extends Model
{
    use LogsActivity;

    /**
     * Log the changed attributes
     */
    protected static $logAttributes = ['amount'];

    /**
     * Log custom 'log_name' field
     */
    protected static $logName = 'wallet';

    /**
     * @var array
     */
    protected $fillable = ['driver_id', 'amount'];

    /**
     * @param float $amount
     * @param Collection $drivers
     */
    public static function setMassAmount (float $amount, Collection $drivers)
    {
        $drivers->each(static function (Driver $driver) use ($amount) {
            $driver->wallet->addAmount($amount);
        });
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function driver ()
    {
        return $this->belongsTo(Driver::class, 'driver_id');
    }

    /**
     * @param float $amount
     */
    public function addAmount (float $amount)
    {
        $this->update([
            'amount' => $this->amount -= $amount
        ]);
    }

    /**
     * Reduse driver`s wallet amount
     *
     * @param float $amount
     * @return void
     */
    public function reduceAmount (float $amount)
    {
        $this->update([
            'amount' => $this->amount += $amount
        ]);
    }
}
