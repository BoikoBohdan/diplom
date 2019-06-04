<?php

namespace App\Http\Controllers\API\Admin;

use App\{Http\Controllers\Controller, Http\Resources\VehicleTypes\Collection, VehicleType};

class VehicleTypeController extends Controller
{
    public function index ()
    {
        return new Collection(
            VehicleType::get(['id', 'type'])
        );
    }
}
