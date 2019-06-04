<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderCancelReason extends Model
{
    /**
     * @var array
     */
    protected $fillable = [
        'order_id', 'additional_cancel_reason_id', 'drivers_reason', 'admins_reason'
    ];

    /**
     * Related order
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function order ()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    /**
     * Related additional reason
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function additionalReason ()
    {
        return $this->belongsTo(AdditionalCancelReason::class, 'additional_cancel_reason_id');
    }
}
