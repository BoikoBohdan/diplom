<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdditionalCancelReason extends Model
{
    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var array
     */
    protected $fillable = [
        'cancel_reason_id', 'info'
    ];

    /**
     * Get related cancel reason
     *
     * @return BelongsTo
     */
    public function cancelReason ()
    {
        return $this->belongsTo(CancelReason::class, 'cancel_reason_id');
    }
}
