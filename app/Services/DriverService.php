<?php

namespace App\Services;

use App\Http\Resources\{Driver, Drivers\Collection};
use App\Mail\NewDriverMail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class DriverService extends BasicService
{
    /**
     * @param $request
     * @param Model $model
     * @return Collection|\Illuminate\Database\Eloquent\Collection|Model[]
     */
    public function all ($request, Model $model)
    {
        return new Collection(
            $model->indexAll($request)->paginate($request->input('perPage', 10))
        );
    }

    /**
     * @param Model $model
     * @param array $attributes
     * @return mixed|void
     */
    public function store (Model $model, array $attributes)
    {
        $password = Str::random(10);
        $attributes['password'] = bcrypt($password);
        $user = $model->add($attributes);
        $user->driver()->create();

        Mail::to($user)->send(new NewDriverMail($user->getFullName(), $user->email, $password));
    }

    /**
     * @param Model $model
     * @return Driver|Model
     */
    public function edit (Model $model)
    {
        return new Driver($model);
    }

    /**
     * @param Model $model
     * @param array $attributes
     * @return bool|void
     */
    public function update (Model $model, array $attributes)
    {
        $model->change($attributes);
    }

    /**
     * @param Model $model
     * @param $request
     */
    public function bulkDestroy (Model $model, Request $request)
    {
        $model->bulkDestroy($request->data);
    }

    /**
     * @param Model $model
     * @param array $attributes
     */
    public function bulkUpdate (Model $model, array $attributes)
    {
        $model->bulkUpdate($attributes);
    }

    /**
     * @param Model $model
     * @param array $attributes
     */
    public function setCoordinates (Model $model, array $attributes)
    {
        $model->coordinates()->updateOrCreate(
            ['coordinatable_id' => $model->id],
            [
                'lat' => $attributes['lat'],
                'lng' => $attributes['lng']
            ]);
    }
}
