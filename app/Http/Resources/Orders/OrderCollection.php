<?php

namespace App\Http\Resources\Orders;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class OrderCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray ($request)
    {
        return Index::collection($this->collection);
    }

    public function withResponse ($request, $response)
    {
        /* $response->header('X-Total-Count', $this->count());
        $response->header( 'Access-Control-Expose-Headers', 'X-Total-Count'); */
    }

}
