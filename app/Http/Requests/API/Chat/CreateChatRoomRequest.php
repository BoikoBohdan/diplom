<?php

namespace App\Http\Requests\API\Chat;

use App\Http\Requests\API\BaseApiRequest;

class CreateChatRoomRequest extends BaseApiRequest
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
            'reciever_id' => 'required|exists:users,id'
        ];
    }
}
