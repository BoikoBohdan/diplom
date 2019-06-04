<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Restaurant extends Model
{
    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'city',
        'phone',
        'postcode',
        'reference_id',
        'contact_name',
        'country_code',
        'streetaddress'
    ];

    /**
     * @param $item
     * @return mixed
     */
    public static function isExist ($item)
    {
        return self::updateOrCreate(['reference_id' => $item['reference_id']], $item)->id;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orders ()
    {
        return $this->hasMany(Order::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function ratings ()
    {
        return $this->morphMany(Rating::class, 'ratingable');
    }

    /**
     * @return string
     */
    public function getAddressAttribute (): string
    {
        return $this->country_code . ', ' . $this->city . ', ' . $this->streetaddress;
    }
}
