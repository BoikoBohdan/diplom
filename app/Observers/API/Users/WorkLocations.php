<?php

namespace App\Observers\API\Users;

use App\{UsersWorkLocations};

class WorkLocations
{
    /**
     * Handle the users work locations "created" event.
     *
     * @param UsersWorkLocations $model
     * @return void
     */
    public function created (UsersWorkLocations $model)
    {
        //
    }

    /**
     * Handle the users work locations "updated" event.
     *
     * @param UsersWorkLocations $model
     * @return void
     */
    public function updated (UsersWorkLocations $model)
    {
        if ($model->is_active == 1) {
            $model->disableActiveWorkLocation();
        }
    }

    /**
     * Handle the users work locations "deleting" event.
     *
     * @param UsersWorkLocations $model
     * @return void
     */
    public function deleting (UsersWorkLocations $model)
    {
        //$model->coordinates()->delete();
    }

    /**
     * Handle the users work locations "deleted" event.
     *
     * @param UsersWorkLocations $model
     * @return void
     */
    public function deleted (UsersWorkLocations $model)
    {
        //
    }

    /**
     * Handle the users work locations "restored" event.
     *
     * @param UsersWorkLocations $model
     * @return void
     */
    public function restored (UsersWorkLocations $model)
    {
        //
    }

    /**
     * Handle the users work locations "force deleted" event.
     *
     * @param UsersWorkLocations $model
     * @return void
     */
    public function forceDeleted (UsersWorkLocations $model)
    {
        //
    }
}
