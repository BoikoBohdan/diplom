<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VehicleType extends Model
{
    public const LIST_TYPES = [
        1 => 'car',
        2 => 'bicycle',
        3 => 'scooter'
    ];

    /**
     * @var array
     */
    protected $fillable = [
        'type', 'completed_orders_charges', 'cancelled_orders_charges', 'delivery_radius'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function vehicles ()
    {
        return $this->hasMany(Vehicle::class);
    }
}
