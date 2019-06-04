<?php

namespace App\Http\Requests\API\Driver;

use App\Http\Requests\API\BaseApiRequest;

class SetVehicleStatusRequest extends BaseApiRequest
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
            'is_shift' => 'required|boolean'
        ];
    }
}
