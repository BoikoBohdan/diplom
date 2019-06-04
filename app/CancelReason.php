<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CancelReason extends Model
{
    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var array
     */
    protected $fillable = [
        'info'
    ];

    /**
     * Get related cancel order reason
     *
     * @return HasMany
     */
    public function additionalCancelReason ()
    {
        return $this->hasMany(AdditionalCancelReason::class);
    }
}
