<?php

namespace App\Exceptions;

use Illuminate\Http\Exceptions\HttpResponseException as HttpException;

trait HttpResponseException
{
    /**
     * Handle exception
     */
    protected function hasError (string $message, int $code)
    {
        throw new HttpException(response()->json(
            ['error' => $message], $code
        ));
    }
}
