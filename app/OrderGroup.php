<?php

namespace App;

use Illuminate\{Database\Eloquent\Model, Database\Eloquent\Relations\HasMany};

class OrderGroup extends Model
{
    /**
     * Related orders
     * @return HasMany
     */
    public function orders ()
    {
        return $this->hasMany(Order::class);
    }
}
