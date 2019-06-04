<?php

namespace App\Exceptions;

use Illuminate\{Contracts\Validation\Validator,
    Http\Exceptions\HttpResponseException,
    Http\JsonResponse,
    Validation\ValidationException};

trait FormRequestValidationException
{
    /**
     * Handle a failed validation attempt.
     *
     * @param Validator $validator
     * @return void
     *
     */
    protected function hasError (Validator $validator)
    {
        $errors = (new ValidationException($validator))->errors();

        throw new HttpResponseException(response()->json(
            ['error' => $errors], JsonResponse::HTTP_UNPROCESSABLE_ENTITY
        ));
    }
}
