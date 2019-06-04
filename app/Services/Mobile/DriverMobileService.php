<?php

namespace App\Services\Mobile;

use App\{Http\Resources\DriversApp\Driver\Resource, Services\DriverService, User, UsersWorkLocations};
use Illuminate\Database\Eloquent\Model;
use Tymon\JWTAuth\Facades\JWTAuth;

class DriverMobileService extends DriverService
{
    public $user;

    public function __construct ()
    {
        $this->user = JWTAuth::parseToken()->authenticate();
    }

    /**
     * @param Model $model
     * @return Resource|Model
     */
    public function show (Model $model)
    {
        return new Resource($model);
    }

    /**
     * @param User $user
     * @param UsersWorkLocations $location
     */
    public function setActiveWorkLocation (UsersWorkLocations $location)
    {
        $location->update(['is_active' => 1]);
    }

    public function setIsShiftStatus ()
    {

        $driver = $this->user->driver;

        $driver->is_shift
            ? $driver->setIsShiftStatus($driver::SHIFT_STATUSES['inactive'])
            : $driver->setIsShiftStatus($driver::SHIFT_STATUSES['active']);
    }
}
