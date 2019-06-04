<?php

namespace App\Http\Requests\API\Driver;

use App\Http\Requests\API\BaseApiRequest;

class CreateDriver extends BaseApiRequest
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
            'first_name' => 'required|string|min:2|max:255',
            'last_name' => 'required|string|min:2|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|regex:/^[+]([0-9]{3})([0-9]{9})$/',
            'address' => 'required|string|max:255',
            'company_id' => 'required|integer|exists:companies,id',
            'image' => 'nullable|string',
            'status' => 'required|boolean'
        ];
    }

    public function messages ()
    {
        return
            [
                'company_id.required' => 'The company field is required',
                'company_id.exists' => 'The selected company is invalid',
                'status.boolean' => 'Status do not have such value'
            ];
    }
}
