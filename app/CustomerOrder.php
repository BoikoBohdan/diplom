<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CustomerOrder extends Model
{
    /**
     * @var string
     */
    protected $table = 'customer_order';

    /**
     * @var array
     */
    protected $fillable = [
        'customer_id', 'order_id'
    ];

    /**
     * @var bool
     */
    protected $timestamps = false;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function orders ()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function customers ()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }
}
