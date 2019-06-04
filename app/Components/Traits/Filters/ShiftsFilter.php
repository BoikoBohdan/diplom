<?php


namespace App\Components\Traits\Filters;


use Illuminate\Database\Eloquent\Builder;

trait ShiftsFilter
{
    /**
     * @param Builder $query
     * @return Builder
     */
    protected function byRole (Builder $query)
    {
        $user = auth()->user();

        return $user->role->isMasterAdmin()
            ? $query
            : $query->where('company_id', $user->company_id);
    }
}
