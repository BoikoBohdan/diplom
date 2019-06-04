<?php

namespace App;

use App\{Components\Traits\Broadcastable,
    Components\Traits\OrderTrait,
    Http\Trats\Filters\OrdersFilter,
    Jobs\API\Orders\SetOrderStatus};
use Illuminate\{Database\Eloquent\Builder, Database\Eloquent\Model, Support\Collection, Support\Facades\DB};
use Spatie\Activitylog\Traits\LogsActivity;

class Order extends Model
{
    use OrdersFilter;
    use OrderTrait;
    use Broadcastable;
    use LogsActivity;

    /**
     * List of statuses, which order can have
     */
    public const STATUSES = [
        'not_assigned' => 0,
        'assigned' => 1,
        'on_the_way_to_restaurant' => 2,
        'arrived_to_restaurant' => 3,
        'left_restaurant' => 4,
        'arrived_to_customer' => 5,
        'delivered' => 6,
        'cancelled' => 7,
        'cancel_request' => 8,
        'rollback_to_previous' => 9
    ];
    /**
     * Array of order titles
     */
    public const STATUS_TITLES = [
        0 => 'Not Assigned',
        1 => 'Assigned',
        2 => 'On the way to restaurant',
        3 => 'Arrived to restaurant',
        4 => 'Left restaurant',
        5 => 'Arrived to customer',
        6 => 'Delivered',
        7 => 'Cancelled',
        8 => 'Cancel Request',
        9 => 'Rollback to Previous'
    ];
    /**
     * Array of order status colors
     */
    public const STATUS_COLORS = [
        0 => '#424242',
        1 => '#00E676',
        2 => '#C6FF00',
        3 => '#2E7D32',
        4 => '#FF8F00',
        5 => '#D84315',
        6 => '#0277BD',
        7 => '#C62828',
        8 => '#C62728'
    ];
    /**
     * Payment types
     */
    public const PAYMENT_TYPE = [
        'cash' => 1,
        'card' => 2
    ];

    /**
     * Log the changed attributes
     */
    protected static $logAttributes = [
        'status'
    ];

    /**
     * Log only if changed certain attributes - $logAttributes
     */
    protected static $submitEmptyLogs = false;

    /**
     * Ignored attributes for loging
     */
    protected static $ignoreChangedAttributes = [
        'fee',
        'asap',
        'notes',
        'load_type',
        'driver_id',
        'group_guid',
        'time_break',
        'pickup_date',
        'reference_id',
        'payment_info',
        'dropoff_date',
        'time_loading',
        'payment_type',
        'customer_info',
        'time_dropping',
        'shipment_type',
        'restaurant_id',
        'pickup_time_to',
        'order_group_id',
        'dropoff_time_to',
        'previous_status',
        'pickup_time_from',
        'enforce_signature',
        'dropoff_time_from',
        'updated_at'
    ];

    /**
     * Log custom 'log_name' field
     */
    protected static $logName = 'order';

    /**
     * List of fields that are assigned to trigger broadcast on orders page
     *
     * @var array
     */
    public $broadcastTrigger = [
        'fee',
        'status',
        'pickup_date',
        'reference_id',
        'restaurant_id',
        'pickup_time_from',
        'dropoff_time_from'
    ];
    /**
     * List of fields that are assigned to search method
     *
     * @var array
     */
    public $searchable = [
        'fee',
        'status',
        'pickup_date',
        'reference_id',
        'previous_status',
        'restaurant_name',
        'pickup_time_from',
        'dropoff_postcode',
        'dropoff_time_from',
        'restaurant_postcode'
    ];

    /**
     * Default value of items per page
     *
     * @var integer
     */
    protected $perPage = 10;

    /**
     * Fields available for mass assigment
     *
     * @var array
     */
    protected $fillable = [
        'fee',
        'asap',
        'notes',
        'status',
        'load_type',
        'driver_id',
        'group_guid',
        'time_break',
        'pickup_date',
        'reference_id',
        'payment_info',
        'dropoff_date',
        'time_loading',
        'payment_type',
        'customer_info',
        'time_dropping',
        'shipment_type',
        'restaurant_id',
        'pickup_time_to',
        'order_group_id',
        'dropoff_time_to',
        'previous_status',
        'pickup_time_from',
        'enforce_signature',
        'dropoff_time_from'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function locations ()
    {
        return $this->hasMany(OrdersLocations::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function products ()
    {
        return $this->hasMany(OrdersProducts::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function restaurant ()
    {
        return $this->belongsTo(Restaurant::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function company ()
    {
        return $this->belongsTo(Company::class, 'order_group_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function customers ()
    {
        return $this->belongsToMany(Customer::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function group ()
    {
        return $this->belongsTo(OrderGroup::class, 'order_group_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function waypoints ()
    {
        return $this->hasMany(Waypoint::class);
    }

    /**
     * @return Collection
     */
    public function getStatusesAttribute ()
    {
        return collect(self::STATUSES)->reject(static function ($status) {
            return $status == self::STATUSES['rollback_to_previous'];
        })->map(static function ($status) {
            return [
                'key' => $status,
                'value' => self::STATUS_TITLES[$status]
            ];
        });
    }

    /**
     * @return Collection
     */
    public function getCoordinatesAttribute ()
    {
        return collect([
            'pickup' => $this->pickup_location->coordinates,
            'dropoff' => $this->dropoff_location->coordinates
        ]);
    }

    /**
     * @return mixed
     */
    public function getPickupLocationAttribute ()
    {
        return $this->locations->where('type', OrdersLocations::LOCATION_TYPE['pickup'])->first();
    }

    /**
     * @return mixed
     */
    public function getDropoffLocationAttribute ()
    {
        return $this->locations->where('type', OrdersLocations::LOCATION_TYPE['dropoff'])->first();
    }

    /**
     * @param $fee
     * @return float
     */
    public function getFeeAttribute ($fee)
    {
        return convertIntegerToFloat($fee);
    }

    /**
     * @param Builder $query
     * @return Builder|null
     */
    public function scopeByRole (Builder $query)
    {
        return $this->byRole($query);
    }

    /**
     * @param Builder $query
     * @param $request
     * @return Builder
     */
    public function scopeGetAll (Builder $query, $request)
    {
        $query->byRole()
            ->select(
                'id',
                'fee',
                'status',
                'pickup_date',
                'reference_id',
                'restaurant_id',
                'pickup_time_from',
                'dropoff_time_from'
            )->with(
                'restaurant',
                'locations',
                'drivers',
                'drivers.wallet'
            )->when($request->order_status, static function ($query) use ($request) {
                if (!$request->order_status || $request->order_status === '[]') {
                    return $query;
                }

                return $query->whereIn('status', explode(',', rtrim(str_replace('"', '', $request->order_status), ',')));

            })->when($request->filter, function ($query) use ($request) {
                $needle = array_search($request->name, $this->getSearchable(), true);

                if (!$request->name || $request->name === 'all') {
                    return $this->byAvailable($query, $request->filter);

                } elseif ($needle !== false) {

                    $field = $this->getSearchable()[$needle];

                    if ($field === 'restaurant_name') {
                        return $this->byRestaurantName($query, $request->filter);

                    } else if ($field === 'restaurant_postcode') {
                        return $this->byRestaurantPostcode($query, $request->filter);

                    } else if ($field === 'dropoff_postcode') {
                        return $this->byDropoffPostcode($query, $request->filter);
                    } else {
                        return $query->where($field, 'LIKE', "%{$request->filter}%");
                    }
                }

                return $query;

            })->when($request->sort, static function ($query) use ($request) {
                if ($request->sort === 'restaurant_name') {
                    return $query->join('restaurants', 'orders.restaurant_id', '=', 'restaurants.id')
                        ->orderBy('restaurants.name', $request->input('order', 'DESC'))
                        ->select('orders.*');

                } elseif ($request->sort === 'restaurant_postcode') {
                    return $query->join('restaurants', 'orders.restaurant_id', '=', 'restaurants.id')
                        ->orderBy('restaurants.postcode', $request->input('order', 'DESC'))
                        ->select('orders.*');

                } elseif ($request->sort === 'dropoff_postcode') {
                    return $query->join('orders_locations', 'orders.id', '=', 'orders_locations.order_id')
                        ->where('type', OrdersLocations::LOCATION_TYPE['dropoff'])
                        ->orderBy('orders_locations.postcode', $request->input('order', 'DESC'))
                        ->select('orders.*');

                } elseif ($request->sort === 'order_status') {
                    return $query->orderBy('orders.status', $request->input('order', 'DESC'));
                } else {
                    return $query->orderBy('orders.status', 'ASC')
                        ->orderBy($request->input('sort', 'id'), $request->input('order', 'DESC'));

                }
            });

        return $query;
    }

    /**
     * @return array
     */
    public function getSearchable (): array
    {
        return $this->searchable;
    }

    /**
     * @param $query
     * @param $request
     * @return mixed
     */
    public function scopeGetGodsEyeOrders ($query, $request)
    {
        return $query->byRole()
            ->select(
                'id',
                'fee',
                'status',
                'pickup_date',
                'dropoff_date',
                'reference_id',
                'restaurant_id',
                'payment_type',
                'pickup_time_to',
                'dropoff_time_to',
                'previous_status',
                'pickup_time_from',
                'dropoff_time_from'
            )
            ->with(
                'drivers:drivers.id,user_id',
                'drivers.user:id,first_name,last_name,role_id',
                'drivers.orders:orders.id,status',
                'driverOrder:id,driver_id,order_id,position',
                'restaurant:id,name',
                'locations:id,order_id,type,lat,lng,contact_name,streetaddress,postcode,city'
            )
            ->when(isset($request->filter), static function ($order) use ($request) {
                if ($request->name === 'driver') {
                    return $order->whereHas('drivers', static function ($d) use ($request) {
                        $d->where('drivers.user_id', $request->filter);
                    });
                }
                return !$request->name || $request->name === 'all'
                    ? $order
                    : $order->where($request->name, 'LIKE', "%{$request->filter}%");
            })
            ->orderBy('status', 'ASC')
            ->orderBy('pickup_date', 'DESC')
            ->orderBy('pickup_time_from', 'DESC');
    }

    /**
     * Set previous status for order
     *
     * @return void
     */
    public function setPreviousStatus ()
    {
        $this->previous_status = $this->getOriginal('status');
    }

    /**
     * Set order waypoints
     */
    public function setWaypoints ()
    {
        $this->locations->each(function (OrdersLocations $location) {
            Waypoint::create([
                'type' => array_flip(OrdersLocations::LOCATION_TYPE)[$location->type],
                'number' => $this->getPosition(),
                'order_id' => $this->id
            ]);
        });
    }

    /**
     * Get Orders position in gods eye
     *
     * @return integer
     */
    public function getPosition ()
    {
        $driver = $this->drivers->first();

        return $driver
            ? $this->driverOrder->first()->position
            : 0;
    }

    /**
     * @param int $group
     */
    public function setGroup (int $group)
    {
        $this->update([
            'order_group_id' => $group
        ]);
    }

    /**
     * Get list of drivers names assigned for order
     *
     * @return string
     */
    public function getDriversList ()
    {
        return $this->drivers->map(static function ($driver) {
            return $driver->user->full_name;
        })->implode(',');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function driverOrder ()
    {
        return $this->hasMany(DriverOrder::class, 'order_id', 'id');
    }

    /**
     * @param Builder $query
     * @return array
     */
    public function scopeStatistics (Builder $query)
    {
        $orders = $query->byRole()->get(['id', 'status']);

        $statistics = [];

        foreach (self::STATUSES as $key => $status) {
            if ($status != self::STATUSES['rollback_to_previous']) {

                $statistics[] = setStatisticsItem(
                    $key, self::STATUS_TITLES[$status], $orders->where('status', $status)->count()
                );
                $statistics[$status]['color'] = self::STATUS_COLORS[$status];
            }
        }

        return $statistics;
    }

    /**
     * @return array
     */
    public function getDeliveryStatistics ()
    {
        $statistics[] = setStatisticsItem('avg_delivery_time', 'Average Delivery Time', 0);
        $statistics[] = setStatisticsItem('avg_pickup_time', 'Average Pickup Time', 0);

        return $statistics;
    }

    /**
     * @return mixed
     */
    public function getRatings ()
    {
        return $this->restaurant->ratings->merge($this->customers->first()->ratings);
    }

    /**
     * @param $data
     */
    public function add ($data)
    {
        DB::transaction(function () use ($data) {
            $restaurant = Restaurant::isExist($data['locations'][0]);

            $order = $this->create(collect($data['order'])->merge(['restaurant_id' => $restaurant])->toArray());

            if (isset($data['locations'], $data['products'])) {

                $data['locations']->each(static function ($location) use ($order) {
                    $order->locations()->create($location);
                });

                $data['products']->each(static function ($product) use ($order) {
                    $order->products()->create($product);
                });

            } else {
                throw new \RuntimeException('Not enough information to store order', 422);
            }
        }, 10);
    }

    /**
     * @param array $attributes
     */
    public function change (array $attributes)
    {
        dispatch_now(new SetOrderStatus($this, $attributes['status']));
    }

    /**
     * Rollback to previous status and remove previous status
     *
     * @return void
     */
    public function rollbackToPreviousStatus ()
    {
        $this->status = $this->getOriginal('previous_status');
        $this->previous_status = null;
    }

    /**
     * @return bool
     */
    public function statusHasChanged (): bool
    {
        return $this->getOriginal('status') !== $this->status;
    }

    /**
     * Remove all assigned drivers from order
     *
     * @return void
     */
    public function detachDrivers ()
    {
        $this->drivers()->detach();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function drivers ()
    {
        return $this->belongsToMany(Driver::class);
    }

    /**
     * @return array
     */
    public function getBroadcastTrigger (): array
    {
        return $this->broadcastTrigger;
    }

    /**
     * @return bool
     */
    public function isCancelReasonRelation (): bool
    {
        return $this->cancelReason()->exists();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function cancelReason ()
    {
        return $this->hasOne(OrderCancelReason::class);
    }

}
