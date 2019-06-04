<?php

namespace App\Http\Resources\Chat;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RoomResource extends JsonResource
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
            'users' => $this->users->reject(function ($user) {
                return $user->id === auth()->id();
            })->pluck('id'),
            'last_message' => new LastMessageResource($this->messages->last())
        ];
    }
}
