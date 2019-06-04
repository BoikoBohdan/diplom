<?php

namespace App\Http\Resources\GodsEye\Drivers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class DriversCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray ($request)
    {
        return DriversResource::collection($this->collection);
    }
}
