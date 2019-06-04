<?php

namespace App\Http\Requests\API\WorkLocations;

use App\Http\Requests\API\BaseApiRequest;

class WorkLocationUpdate extends BaseApiRequest
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
            'user_id' => 'integer|exists:users,id',
            'city_id' => 'integer|exists:cities,id'
        ];
    }
}
