<?php

namespace App\Http\Resources\CancelReasons\AdditionalReason;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class Collection extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray ($request)
    {
        return Index::collection($this);
    }
}
