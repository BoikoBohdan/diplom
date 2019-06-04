<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Coordinate extends Model
{
    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var array
     */
    protected $fillable = [
        'lat', 'lng', 'coordinatable_id', 'coordinatable_type'
    ];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function coordinatable ()
    {
        return $this->morphTo();
    }

    /**
     * Get coordinates
     *
     * @return array
     */
    public function getDataAttribute ()
    {
        return [
            'lat' => $this->lat,
            'lng' => $this->lng
        ];
    }
}
