<?php

namespace App;

use Illuminate\{Database\Eloquent\Model,
    Database\Eloquent\Relations\BelongsTo,
    Database\Eloquent\Relations\MorphMany,
    Support\Collection};

class UsersWorkLocations extends Model
{
    public const LOCATIONS_LIMIT = 3;

    /**
     * @var array
     */
    protected $fillable = [
        'user_id',
        'city_id',
        'is_active'
    ];

    /**
     * @param Collection $locations
     * @return mixed
     */
    public static function isActive (Collection $locations)
    {
        return $locations->filter(static function ($location) {
            return $location->is_active === 1;
        })->first();
    }

    /**
     * @return BelongsTo
     */
    public function driver ()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsTo
     */
    public function city ()
    {
        return $this->belongsTo(City::class);
    }

    /**
     * Get related coordinates
     *
     * @return MorphMany
     */
    public function coordinates ()
    {
        return $this->morphMany(Coordinate::class, 'coordinatable');
    }

    /**
     * @param array $data
     * @return bool
     */
    public function add (array $data)
    {
        if (self::where('user_id', $data['user_id'])->count() < self::LOCATIONS_LIMIT) {
            $this->create($data);
            return true;
        }
        throw new \RuntimeException('The limit of ' . self::LOCATIONS_LIMIT . ' locations per driver is reached', 422);
    }

    /**
     * Disable all active work locations
     *
     * @return void
     */
    public function disableActiveWorkLocation ()
    {
        self::where('user_id', $this->user_id)->where('id', '!=', $this->id)->get()->each(static function (self $location) {
            $location->update(['is_active' => 0]);
        });
    }

    /**
     * Get coordinates
     *
     * @return void
     */
    public function getCoordinates ()
    {
        return $this->coordinates->first()->data;
    }
}
