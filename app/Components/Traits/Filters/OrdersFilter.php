<?php

namespace App\Http\Trats\Filters;

use App\OrdersLocations;
use Illuminate\Database\Eloquent\Builder;

trait OrdersFilter
{
    /**
     * Search in orders by restaurant name
     *
     * @param Builder $query
     * @param [type] $searched
     * @return Builder
     */
    protected function byRestaurantName (Builder $query, $searched)
    {
        return $query->whereHas('restaurant', static function ($q) use ($searched) {
            $q->where('name', 'LIKE', "%{$searched}%");
        });
    }

    /**
     * Search in orders by restaurant postcode
     *
     * @param Builder $query
     * @param [type] $searched
     * @return Builder
     */
    protected function byRestaurantPostcode (Builder $query, $searched)
    {
        return $query->whereHas('restaurant', static function ($q) use ($searched) {
            $q->where('postcode', 'LIKE', "%{$searched}%");
        });
    }

    /**
     * Search in orders by dropoff postcode
     *
     * @param Builder $query
     * @param [type] $searched
     * @return Builder
     */
    protected function byDropoffPostcode (Builder $query, $searched)
    {
        return $query->whereHas('locations', static function ($q) use ($searched) {
            $q->where('type', OrdersLocations::LOCATION_TYPE['dropoff'])
                ->where('postcode', 'LIKE', "%{$searched}%");
        });
    }

    /**
     * Search in orders by all available fields
     *
     * @param Builder $query
     * @param [type] $searched
     * @return Builder
     */
    protected function byAvailable (Builder $query, $searched)
    {
        return $query->where('orders.reference_id', 'LIKE', "%{$searched}%")
            ->orWhere('pickup_date', 'LIKE', "%{$searched}%")
            ->orWhere('pickup_time_from', 'LIKE', "%{$searched}%")
            ->orWhere('dropoff_time_from', 'LIKE', "%{$searched}%")
            ->orWhere('fee', 'LIKE', "%{$searched}%")
            ->orWhere('status', 'LIKE', "%{$searched}%")
            ->orWhereHas('restaurant', static function ($q) use ($searched) {
                $q->where('name', 'LIKE', "%{$searched}%")
                    ->orWhere('postcode', 'LIKE', "%{$searched}%");
            })
            ->orWhereHas('locations', static function ($q) use ($searched) {
                $q->where('type', OrdersLocations::LOCATION_TYPE['dropoff'])
                    ->where('postcode', 'LIKE', "%{$searched}%");
            });
    }

    /**
     * @param Builder $query
     * @return Builder|null
     */
    protected function byRole (Builder $query)
    {
        $admin = auth()->user();

        return $admin->role->isMasterAdmin()
            ? $query
            : $query->where('group_guid', $admin->company->guid);
    }
}
