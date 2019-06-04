<?php

namespace App\Http\Requests\API\Mobile;

use App\Http\Requests\API\BaseApiRequest;

class ChangeDriverCoordinatesRequest extends BaseApiRequest
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
            'lat' => 'required|string',
            'lng' => 'required|string',
        ];
    }
}
