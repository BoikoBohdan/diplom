<?php

namespace App;

use Illuminate\{Auth\Access\AuthorizationException,
    Database\Eloquent\Model,
    Database\Eloquent\Relations\BelongsTo,
    Support\Facades\DB};

class DriverOrder extends Model
{
    /**
     * @var string
     */
    protected $table = 'driver_order';

    /**
     * @var array
     */
    protected $fillable = [
        'driver_id', 'order_id'
    ];

    /**
     * Set number for new assigned order
     *
     * @param integer $driver
     * @return integer
     */
    public static function setPosition (int $driver)
    {
        return self::where('driver_id', $driver)->count() + 1;
    }

    /**
     * Assign drivers to orders
     *
     * @param array $attributes
     * @return void
     * @throws AuthorizationException
     */
    public static function add (array $attributes)
    {
        if (auth()->user()->can('create', self::class)) {

            $orders = Order::whereIn('id', $attributes['orders'])->get();
            $drivers = User::whereIn('id', $attributes['drivers'])->with('driver')->get();

            DB::transaction(static function () use ($orders, $drivers) {
                $orders->each(static function (Order $order) use ($drivers) {
                    $drivers->each(static function (User $user) use ($order) {
                        self::where('order_id', $order->id)->where('driver_id', $user->driver->id)->count() === 0
                            ? self::create([
                            'order_id' => $order->id,
                            'driver_id' => $user->driver->id
                        ])
                            : false;
                    });
                });
            });
        } else {
            throw new AuthorizationException();
        }

    }

    /**
     * Get related order
     *
     * @return BelongsTo
     */
    public function order ()
    {
        return $this->belongsTo(Order::class);
    }
}
