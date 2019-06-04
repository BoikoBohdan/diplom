<?php

namespace App\Services;

use App\Http\Resources\WorkLocations\Index;
use Illuminate\Database\Eloquent\Model;


class WorkLocationService extends BasicService
{
    /**
     * @param Model $model
     * @param array $attributes
     * @return mixed|void
     */
    public function store (Model $model, array $attributes)
    {
        $model->add($attributes);
    }

    /**
     * @param Model $model
     * @return Index|Model
     */
    public function edit (Model $model)
    {
        return new Index($model);
    }
}
