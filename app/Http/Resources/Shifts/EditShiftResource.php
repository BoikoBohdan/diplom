<?php

namespace App\Http\Resources\Shifts;

use App\Shift;
use Illuminate\Http\Resources\Json\JsonResource;
use App\City;
use App\Http\Resources\Cities\CityCollection;

class EditShiftResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray ($request)
    {
        foreach(Shift::MEALS_TITLES as $key=>$value){
            $meals[] = ['id'=> $key, 'title' => $value];
        };
        $cities = new CityCollection(City::all());
        return [
            'id' => $this->id,
            'date' => $this->date,
            'city_id' => $this->city_id,
            'start' => $this->start,
            'end' => $this->end,
            'meal' => $this->meal,
            'meals' => $meals,
            'cities' => $cities
        ];
    }
}
