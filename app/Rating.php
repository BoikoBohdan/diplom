<?php

namespace App;

use Illuminate\{Database\Eloquent\Model, Database\Eloquent\ModelNotFoundException};

class Rating extends Model
{
    public const RESTAURANT = 'restaurant';

    public const CUSTOMER = 'customer';

    /**
     * @var array
     */
    protected $fillable = [
        'rating', 'message', 'ratingable_id', 'ratingable_type', 'driver_id'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function ratingable ()
    {
        return $this->morphTo();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function driver ()
    {
        return $this->belongsTo(Driver::class, 'driver_id');
    }

    /**
     * @param string $value
     */
    public function setRatingableTypeAttribute (string $value)
    {
        if ($value === self::RESTAURANT) {

            $value = Restaurant::class;

        } else if ($value === self::CUSTOMER) {

            $value = Customer::class;

        } else {
            throw new ModelNotFoundException('Model not found', 500);
        }

        $this->attributes['ratingable_type'] = $value;
    }
}
