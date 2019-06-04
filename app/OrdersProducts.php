<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrdersProducts extends Model
{
    /**
     * @var array
     */
    protected $fillable = [
        'fee',
        'name',
        'note',
        'type',
        'image',
        'order_id',
        'quantity',
        'unit_type',
        'reference_id'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function order ()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * @param $fee
     * @return float
     */
    public function getFeeAttribute ($fee)
    {
        return convertIntegerToFloat($fee);
    }
}
