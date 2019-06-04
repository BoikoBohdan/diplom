<?php

namespace App\Http\Resources\Shifts;

use App\Shift;
use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class ShiftResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray ($request)
    {
        $meals = Shift::MEALS_TITLES;
        return [
            'id' => $this->id,
            'date' => Carbon::parse($this->date)->format('d-m-Y'),
            'city' => $this->city->name,
            'start' => $this->start,
            'end' => $this->end,
            'meal' => $meals[$this->meal],
            'drivers' => $this->when(auth()->user()->isSomeAdmin(), $this->drivers->count()),
            'assigned' => $this->when(auth()->user()->driver, function(){
                if($this->drivers->contains(auth()->user()->driver->id)){
                    return true;
                } else {
                    return false;
                }
            })
        ];
    }
}
