<?php

namespace App\Services;

use App\Http\Resources\Vehicles\Collection;
use App\Http\Resources\Vehicles\Index;
use App\Vehicle;
use Illuminate\Database\Eloquent\Model;

class DriverVehicleService extends BasicService
{
    /**
     * @param $request
     * @param Model $model
     * @return Collection|\Illuminate\Database\Eloquent\Collection|Model[]
     */
    public function all ($request, Model $model)
    {
        return new Collection(
            $model->ofAll()->paginate($request->input('perPage', $model->perPage))
        );
    }

    /**
     * @param Model $model
     * @return Index|Model
     */
    public function edit (Model $model)
    {
        return new Index($model);
    }

    /**
     * @param Vehicle $vehicle
     * @param array $attributes
     */
    public function setStatus (Vehicle $vehicle, array $attributes)
    {
        $vehicle->update($attributes);
    }
}
