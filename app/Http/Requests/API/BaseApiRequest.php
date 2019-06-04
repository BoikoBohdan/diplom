<?php

namespace App\Http\Requests\API;

use App\Exceptions\FormRequestValidationException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class BaseApiRequest extends FormRequest
{
    use FormRequestValidationException;

    public function failedValidation (Validator $validator)
    {
        $this->hasError($validator);
    }
}
