<?php

namespace App\Http\Resources\Rating;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class Resource extends JsonResource
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
            'score' => $this->rating,
            'message' => $this->message,
            'ratingable_type' => strtolower(class_basename($this->ratingable_type)),
            'ratingable_id' => $this->ratingable_id
        ];
    }
}
