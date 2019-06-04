<?php

namespace App\Http\Requests\API\Mobile;

use App\Http\Requests\API\BaseApiRequest;
use App\Rules\API\CheckRatingableType;

class CreateRatingRequest extends BaseApiRequest
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
            'message' => 'string',
            'rating' => 'required|numeric',
            'ratingable_id' => 'required|integer',
            'ratingable_type' => [
                'required',
                'string',
                new CheckRatingableType($this->get('ratingable_type'))
            ]
        ];
    }

    /**
     * Validation messages
     *
     * @return array
     */
    public function messages ()
    {
        return [
            'ratingable_type' => [
                'required' => 'Type is required',
            ],
            'ratingable_id' => [
                'required' => 'ID is required'
            ]
        ];
    }
}
