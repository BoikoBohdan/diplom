<?php

namespace App\Http\Requests\API\WorkLocations;

use App\Http\Requests\API\BaseApiRequest;


class WorkLocationCreate extends BaseApiRequest
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
            'user_id' => 'required|integer|exists:users,id',
            'city_id' => 'required|integer|exists:cities,id',
        ];
    }
}
