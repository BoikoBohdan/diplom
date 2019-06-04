<?php

namespace App;

use Illuminate\{Database\Eloquent\Builder, Database\Eloquent\Model, Support\Collection};

class Vehicle extends Model
{
    /**
     * Limit of vehicles per driver
     */
    public const VEHICLES_LIMIT = 5;

    /**
     * Default value of items per page
     *
     * @var integer
     */
    protected $perPage = 10;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'driver_id', 'vehicle_type_id', 'name', 'is_shift'
    ];

    /**
     * Check for active driver`s vehicle
     *
     * @param Collection $vehicles
     * @return self|null
     */
    public static function isActive (Collection $vehicles)
    {
        return $vehicles->filter(static function (Vehicle $vehicle) {
            return $vehicle->is_shift === 1;
        })->first();
    }

    /**
     * Get related driver
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function driver ()
    {
        return $this->belongsTo(Driver::class);
    }

    /**
     * Get related vehicle type
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function vehicleType ()
    {
        return $this->belongsTo(VehicleType::class);
    }

    /**
     * Get related documents
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function documents ()
    {
        return $this->morphMany(Document::class, 'documentable');
    }

    /**
     * @param Builder $query
     * @return Builder
     */
    public function scopeOfAll (Builder $query)
    {
        return $query;
    }

    /**
     * @param Builder $query
     * @return Builder
     */
    public function scopeAreActive (Builder $query)
    {
        return $query->where('is_shift', 1);
    }

    /**
     * @param array $attributes
     */
    public function add (array $attributes)
    {
        if ($this->isLimited(Driver::find($attributes['driver_id']))) {
            throw new \RuntimeException('Limit is reached', 422);
        }

        $this->create($attributes);
    }

    /**
     * @param Driver $driver
     * @return bool
     */
    public function isLimited (Driver $driver): bool
    {
        return $driver->vehicles->count() >= self::VEHICLES_LIMIT;
    }

    /**
     * Check if vehicle is active
     *
     * @return boolean
     */
    public function isShift ()
    {
        $this->is_shift == true
            ? $this->disableActiveVehicles()
            : false;
    }

    /**
     * Disable is_shift status to vehicles if status has changed
     */
    public function disableActiveVehicles ()
    {
        self::where('driver_id', $this->driver_id)
            ->where('id', '!=', $this->id)
            ->update(['is_shift' => 0]);
    }
}
