<?php

namespace App\Http\Resources\Members;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'full_name' => $this->full_name,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'role' => $this->getRoleName(),
            'role_id' => $this->role->id,
            'company' => $this->company->name ?? null,
            'company_id' => $this->company->id ?? null,
            'address' => $this->address,
            'phone' => $this->phone,
            'photo' => $this->when($this->image, $this->image),
            'status' => $this->status
        ];
    }
}
