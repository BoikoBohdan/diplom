<?php

namespace App\Components\Traits\Filters;

trait UsersFilter
{
    public function byCompany (\Illuminate\Database\Eloquent\Builder $query, $company)
    {
        $admin = auth()->user();

        return $admin->role->isMasterAdmin()
            ? $query
            : $query->where('company_id', $company);
    }
}
