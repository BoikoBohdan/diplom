<?php

namespace App\Http\Requests\API\Documents;

use App\Http\Requests\API\BaseApiRequest;

class CreateDocumentRequest extends BaseApiRequest
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
            'file' => 'required|string',
        ];
    }
}
