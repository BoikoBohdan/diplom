<?php

namespace App\Http\Resources\DriversApp\Shifts;

use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Http\Resources\Shifts\ShiftResource;
use Carbon\Carbon;

class ShiftsForDriverCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        /* $months = $this->map(function($item){
            $date = Carbon::parse($item->date);
            return $date->format('F');
        });
        $cities = $this->map(function($item){
            return $item->city->name;
        }); */

        return  ShiftResource::collection($this->collection)->groupBy('date');
            /* 'months' => $months->unique()->values(),
            'cities' => $cities->unique()->values() */
    }
}
