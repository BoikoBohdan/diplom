<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    /**
     * @var array
     */
    protected $fillable = [
        'contact_name', 'phone', 'phone2', 'postcode', 'city', 'country_code', 'streetaddress'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function orders ()
    {
        return $this->belongsToMany(Order::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function ratings ()
    {
        return $this->morphMany(Rating::class, 'ratingable');
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function coordinates ()
    {
        return $this->morphMany(Coordinate::class, 'coordinatable');
    }

    /**
     * Get address attribute
     *
     * @return string
     */
    public function getAddressAttribute (): string
    {
        return $this->country_code . ', ' . $this->city . ', ' . $this->streetaddress;
    }
}
