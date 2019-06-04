<?php

namespace App\Http\Resources\CancelReasons;

use App\Http\Resources\CancelReasons\AdditionalReason\Collection;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class Index extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray ($request)
    {
        return [
            'id' => $this->id,
            'info' => $this->info,
            'additional_info' => new Collection($this->additionalCancelReason)
        ];
    }
}
