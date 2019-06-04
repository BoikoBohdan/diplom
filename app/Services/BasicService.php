<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Model;
use Tymon\JWTAuth\Facades\JWTAuth;

abstract class BasicService
{
    /**
     * @var User $user
     */
    public $user;

    public function __construct() {
        $this->user =  JWTAuth::parseToken()->authenticate();
    }

    /**
     * @param $request
     * @param Model $model
     * @return \Illuminate\Database\Eloquent\Collection|Model[]
     */
    public function all ($request, Model $model)
    {
        return $model->all();
    }

    /**
     * @param Model $model
     * @param array $attributes
     * @return bool
     */
    public function update (Model $model, array $attributes)
    {
        return $model->update($attributes);
    }

    /**
     * @param Model $model
     * @param array $attributes
     * @return mixed
     */
    public function store (Model $model, array $attributes)
    {
        return $model->create($attributes);
    }

    /**
     * @param Model $model
     * @return bool|null
     * @throws \Exception
     */
    public function destroy (Model $model)
    {
        return $model->delete();
    }

    /**
     * @param Model $model
     * @return Model
     */
    public function show (Model $model)
    {
        return $model;
    }

    /**
     * @param Model $model
     * @return Model
     */
    public function edit (Model $model)
    {
        return $model;
    }
}
