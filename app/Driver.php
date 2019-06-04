<?php

namespace App;

use App\Components\Traits\Filters\DriversFilter;
use Illuminate\Database\Eloquent\{Builder,
    Model,
    Relations\BelongsTo,
    Relations\BelongsToMany,
    Relations\HasMany,
    Relations\HasOne,
    Relations\MorphMany,
    SoftDeletes};

class Driver extends Model
{
    use SoftDeletes;
    use DriversFilter;

    /**
     * List of drivers shift statuses
     *
     * @var array
     */
    public const SHIFT_STATUSES = [
        'inactive' => 0,
        'active' => 1
    ];
    /**
     * Fields available for search
     *
     * @var array
     */
    public $searchable = [
        'first_name', 'last_name', 'phone', 'status', 'full_name'
    ];
    /**
     * Fields available for mass assigment
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'rating', 'is_shift'
    ];
    /**
     * Undocumented variable
     *
     * @var array
     */
    protected $dates = [
        'deleted_at'
    ];

    /**
     * Get related user
     *
     * @return BelongsTo
     */
    public function user ()
    {
        return $this->belongsTo(User::class)->withTrashed();
    }

    /**
     * Get related vehicles
     *
     * @return HasMany
     */
    public function vehicles ()
    {
        return $this->hasMany(Vehicle::class);
    }

    /**
     * Get related documents
     *
     * @return MorphMany
     */
    public function documents ()
    {
        return $this->morphMany(Document::class, 'documentable');
    }

    /**
     * Get related pivot table
     *
     * @return HasMany
     */
    public function driverOrder ()
    {
        return $this->hasMany(DriverOrder::class, 'driver_id', 'id');
    }

    /**
     * Get related wallet
     *
     * @return HasOne
     */
    public function wallet ()
    {
        return $this->hasOne(Wallet::class);
    }

    /**
     * Related ratings
     *
     * @return HasMany
     */
    public function ratings ()
    {
        return $this->hasMany(Rating::class);
    }

    /**
     *  Related coordinates
     *
     * @return MorphMany
     */
    public function coordinates ()
    {
        return $this->morphMany(Coordinate::class, 'coordinatable');
    }

    /**
     * Get related shifts
     *
     * @return BelongsToMany
     */
    public function shifts ()
    {
        return $this->belongsToMany(Shift::class);
    }

    /**
     * @param Builder $query
     * @return Builder|null
     */
    public function scopeByCompany (Builder $query)
    {
        return $this->byRole($query);
    }

    /**
     * @param Builder $query
     * @param $request
     * @return Builder;
     */
    public function scopeIndexAll (Builder $query, $request)
    {
        $query->byCompany()
            ->with(
                'vehicles',
                'orders',
                'user',
                'user.workLocations.city',
                'wallet'
            )
            ->join('users', 'drivers.user_id', '=', 'users.id')
            ->select('users.*', 'drivers.*')
            ->when($request->filter, function ($query) use ($request) {
                $needle = array_search($request->name, $this->getSearchable(), true);

                if (!$request->name || $request->name === 'all') {
                    return $this->byAvailable($query, $request->filter);

                } elseif ($needle !== false) {

                    $field = $this->getSearchable()[$needle];

                    return $field === 'full_name'
                        ? $this->byFullName($query, $request->filter)
                        : $this->byField($query, $field, $request->filter);

                } else {
                    return $query;

                }
            })->when($request->sort, static function ($query) use ($request) {
                if ($request->sort === 'full_name') {
                    return $query->orderBy('first_name', $request->input('order', 'DESC'))
                        ->orderBy('last_name', $request->input('order', 'DESC'));

                } elseif ($request->sort === 'phone') {
                    return $query->orderBy('users.phone', $request->input('order', 'DESC'));

                } elseif ($request->sort === 'assigned_orders') {
                    return $query->orderBy('users.id', $request->input('order', 'DESC'));

                } elseif ($request->sort === 'status') {
                    return $query->orderBy('users.status', $request->input('order', 'DESC'));

                } elseif ($request->sort === 'vehicle') {
                    return $query->join('vehicles', 'drivers.id', '=', 'vehicles.id')
                        ->where('vehicles.is_shift', 1)
                        ->orderBy('vehicles.vehicle_type_id', $request->input('order', 'DESC'));
                } else {
                    return $query->orderBy('users.id', $request->input('order', 'DESC'));
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
     * @param Builder $query
     * @return mixed
     */
    public function scopeGetGodsEyeDrivers (Builder $query)
    {
        return $query->byCompany()
            ->whereHas('user.workLocations')
            ->whereHas('coordinates')
            ->whereHas('vehicles', static function ($q) {
                return $q->where('is_shift', 1);
            })
            ->where('is_shift', 1)
            ->with('user:id,first_name,last_name',
                'vehicles:id,driver_id,name,is_shift,vehicle_type_id',
                'vehicles.vehicleType:id,type',
                'orders:orders.id,status',
                'orders.locations:id,order_id,type,lat,lng',
                'orders.waypoints:id,order_id,type,number,created_at',
                'coordinates:id,coordinatable_id,coordinatable_type,lat,lng')
            ->select('id', 'user_id');
    }

    /**
     * Get drivers statistics
     *
     * @param Builder $query
     * @return array
     */
    public function scopeStatistics (Builder $query)
    {
        $query->byCompany();

        $all = $query->count();

        $active = $query->whereHas('vehicles', static function ($query) {
            return $query->areActive();
        })->count();

        return [
            setStatisticsItem('active', 'Active', $active),
            setStatisticsItem('inactive', 'Inactive', $all - $active)
        ];
    }

    /**
     * Count stops for driver
     *
     * @return integer
     */
    public function countStops (): int
    {
        return $this->orders->filter(static function (Order $order) {
            return $order->status === Order::STATUSES['assigned']
                || $order->status === Order::STATUSES['arrived_to_restaurant']
                || $order->status === Order::STATUSES['arrived_to_customer'];

        })->map(static function (Order $order) {
            switch ($order->status) {
                case Order::STATUSES['assigned']:
                    return 2;
                    break;
                case Order::STATUSES['arrived_to_restaurant']:
                    return 1;
                    break;
                case Order::STATUSES['arrived_to_customer']:
                    return 0;
                    break;
                default:
                    return 0;
                    break;
            }
        })->sum();
    }

    /**
     * Get related orders
     *
     * @return BelongsToMany
     */
    public function orders ()
    {
        return $this->belongsToMany(Order::class);
    }

    /**
     * Update drivers is_shift attribute
     *
     * @param int $status
     */
    public function setIsShiftStatus (int $status)
    {
        $this->update([
            'is_shift' => $status
        ]);
    }
}
