<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Waypoint extends Model
{
    public const TYPE_TITLES = [
        0 => 'A',
        1 => 'B'
    ];

    /**
     * Fields available for mass assigment
     *
     * @var array
     */
    protected $fillable = [
        'order_id', 'type', 'number'
    ];

    /**
     * Related order
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function order ()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    /**
     * Set waypoints type attribute
     *
     * @param string $type
     * @return void
     */
    public function setTypeAttribute (string $type)
    {
        return $this->attributes['type'] = OrdersLocations::LOCATION_TYPE[$type];
    }

    /**
     * Get waypoint title
     *
     * @return string
     */
    public function getWaypointTitle (): string
    {
        return $this->number . self::TYPE_TITLES[$this->type];
    }

    public function scopeWaypointsByIds($query, $ids)
    {
        return $query->whereIn('order_id', $ids);
    }
}
