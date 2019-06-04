<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    /**
     * @var array
     */
    protected $fillable = [
        'name'
    ];

    /**
     * @var int
     */
    protected $perPage = 10;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function shifts ()
    {
        return $this->hasMany(Shift::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function workLocations ()
    {
        return $this->hasMany(UsersWorkLocations::class);
    }
}
