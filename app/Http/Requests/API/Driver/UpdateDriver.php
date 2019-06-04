<?php

namespace App\Http\Requests\API\Driver;

use App\Http\Requests\API\BaseApiRequest;

class UpdateDriver extends BaseApiRequest
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

            'first_name' => 'string|min:2|max:255',
            'last_name' => 'string|min:2|max:255',
            'email' => 'email|unique:users,email,' . $this->driver->id . ',id',
            'phone' => 'regex:/^[+]([0-9]{3})([0-9]{9})$/',
            'address' => 'string|max:255',
            'company_id' => 'integer|exists:companies,id',
            'status' => 'boolean',
            'image' => 'nullable|string'
        ];
    }

    public function messages ()
    {
        return
            [
                'company_id.required' => 'The company field is required',
                'status.boolean' => 'Status do not have such value',
                'company_id.exists' => 'The selected company is invalid',
            ];
    }
}
