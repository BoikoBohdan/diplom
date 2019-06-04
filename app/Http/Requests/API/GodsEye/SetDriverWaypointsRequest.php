<?php

namespace App\Http\Requests\API\GodsEye;

use App\Http\Requests\API\BaseApiRequest;
use App\Rules\API\CanSetOrderWaypoint;

class SetDriverWaypointsRequest extends BaseApiRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize ()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules ()
    {
        return [
            'waypoints.*.type' => 'required|string',
            'waypoints.*.number' => 'required|integer',
            'waypoints.*.order_id' => [
                'required',
                'exists:orders,id',
                new CanSetOrderWaypoint($this->user, $this->only(['waypoints']))
            ]
        ];
    }
}
