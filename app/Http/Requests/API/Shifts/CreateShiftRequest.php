<?php

namespace App\Http\Requests\API\Shifts;

use App\Http\Requests\API\BaseApiRequest;

class CreateShiftRequest extends BaseApiRequest
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
            'date' => 'required',
            'city_id' => 'required',
            'start' => 'required|string',
            'end' => 'required|string',
            'meal' => 'required'
        ];
    }
}
