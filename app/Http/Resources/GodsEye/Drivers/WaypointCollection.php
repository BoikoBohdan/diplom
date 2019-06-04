<?php

namespace App\Http\Resources\GodsEye\Drivers;

use Illuminate\Http\Resources\Json\ResourceCollection;

class WaypointCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection|array
     */
    public function toArray ($request)
    {
        return WaypointResource::collection($this->collection->flatten(1)->sortBy('id'));
    }
}
