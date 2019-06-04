<?php

namespace App\Http\Requests\API\Orders;

use App\Http\Requests\API\BaseApiRequest;

class UpdateOrder extends BaseApiRequest
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
            'streetaddress' => 'required|string|min:2',
            'type' => 'required|boolean'
        ];
    }
}
