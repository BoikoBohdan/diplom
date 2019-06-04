<?php

namespace App;

use App\Components\Traits\Broadcastable;
use Illuminate\Database\Eloquent\Model;

class OrdersLocations extends Model
{
    use Broadcastable;

    public const LOCATION_TYPE = [
        'pickup' => 0,
        'dropoff' => 1
    ];

    /**
     * @var array
     */
    public $broadcastable = [
        'streetaddress'
    ];

    /**
     * @var array
     */
    protected $fillable = [
        'order_id', 'reference_id', 'name',
        'type', 'phone', 'postcode', 'contact_name',
        'note', 'lat', 'lng', 'country_code', 'city', 'streetaddress'
    ];

    /**
     * Get related orders
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function order ()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function getCoordinatesAttribute ()
    {
        return collect([
            'lat' => $this->lat,
            'lng' => $this->lng
        ]);
    }

    /**
     * @return string
     */
    public function getAddressAttribute ()
    {
        return $this->streetaddress . ',   ' . $this->postcode . ' ' . $this->city;
    }

    /**
     * @param $query
     * @param int $type
     * @return mixed
     */
    public function scopeByType ($query, int $type)
    {
        return $query->where('type', $type);
    }

    /**
     * @return array|bool
     */
    public function getCustomer ()
    {
        return $this->type === self::LOCATION_TYPE['dropoff']
            ? $this->only('contact_name', 'phone', 'phone2', 'postcode', 'city', 'country_code', 'streetaddress')
            : false;
    }
}
