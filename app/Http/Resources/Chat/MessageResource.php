<?php

namespace App\Http\Resources\Chat;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MessageResource extends JsonResource
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
            'type' => $this->type,
            'message' => $this->message,
            'sender_id' => $this->sender_id,
            'created_at' => $this->created_at->format('Y-m-d H:i:s')
        ];
    }
}
