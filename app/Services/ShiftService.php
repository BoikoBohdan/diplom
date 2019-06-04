<?php

namespace App\Services;

use App\{City, Http\Resources\Cities\CityCollection, Http\Resources\Shifts\ShiftCollection, Shift};

class ShiftService extends BasicService
{
    protected $shift;

    /**
     * ShiftService constructor.
     * @param Shift $shift
     */
    public function __construct ()
    {
        parent::__construct();
        $this->shift = new Shift();
    }

    /**
     * @return ShiftCollection
     */
    public function allShifts ($request, $shifts)
    {
        return new ShiftCollection(
            $shifts->indexAll($request)->paginate($request->input('perPage', $shifts->perPage))
        );
    }

    /**
     * @return ShiftCollection
     */
    /* public function allShiftsForDriver ($request, $shifts)
    {
        return new ShiftCollection(
            $shifts->indexAll($request)->paginate($request->input('perPage', $shifts->perPage))
        );
    } */

    /**
     * @return array
     */
    public function getInfoForCreate ()
    {
        return [
            'meals' => $this->shift->getTitles()->values(),
            'cities' => new CityCollection(City::all())
        ];
    }

}
