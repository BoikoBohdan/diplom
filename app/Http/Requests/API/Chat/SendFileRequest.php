<?php

namespace App\Http\Requests\API\Chat;

use App\Http\Requests\API\BaseApiRequest;

class SendFileRequest extends BaseApiRequest
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
            'chat_room_id' => 'required|exists:chat_rooms,id'
        ];
    }
}
