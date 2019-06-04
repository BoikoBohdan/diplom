<?php

namespace App\Components\Traits\Filters;

use Illuminate\Database\Eloquent\Builder;


trait DriversFilter
{
    /**
     * @param Builder $query
     * @param $searched
     * @return Builder
     */
    protected function byAvailable (Builder $query, $searched)
    {
        return $query->whereRaw("CONCAT(first_name,' ',last_name) LIKE ?", ['%' . $searched . '%'])
            ->orWhere('first_name', 'LIKE', "%{$searched}%")
            ->orWhere('last_name', 'LIKE', "%{$searched}%")
            ->orWhere('phone', 'LIKE', "%{$searched}%")
            ->orWhere('status', 'LIKE', "%{$searched}%");
    }

    /**
     * @param Builder $query
     * @param $searched
     * @return Builder
     */
    protected function byFullName (Builder $query, $searched)
    {
        return $query->whereRaw("CONCAT(first_name,' ',last_name) LIKE ?", ['%' . $searched . '%']);
    }

    /**
     * @param Builder $query
     * @param $field
     * @param $searched
     * @return Builder
     */
    protected function byField (Builder $query, $field, $searched)
    {
        return $query->where($field, 'LIKE', "%{$searched}%");
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
            : $query->whereHas('user', static function ($query) use ($admin) {
                return $query->where('company_id', $admin->company_id);
            });
    }
}
