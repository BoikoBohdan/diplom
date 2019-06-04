<?php

namespace App\Services\Mobile;

use App\{Http\Resources\DriversApp\Shifts\ShiftsForDriverCollection, Services\ShiftService};

class ShiftMobileService extends ShiftService
{
    public function __construct() {
        parent::__construct();
    }

    /**
     * @return ShiftsForDriverCollection
     */
    public function getShiftsForDriver($request, $shifts)
    {
        return new ShiftsForDriverCollection(
            $shifts->shiftsForDriver($request)->get()
        );
    }

}
