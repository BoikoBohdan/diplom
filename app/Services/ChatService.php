<?php

namespace App\Services;

use App\{ChatRoom, Message, Role, User};
use App\Events\API\Chat\RoomCreated;
use App\Http\Resources\Chat\{HistoryCollection, MessageResource, RoomCollection, UsersCollection};
use Tymon\JWTAuth\Facades\JWTAuth;

class ChatService
{
    /**
     * Get list of users for chat
     *
     * @return UsersCollection
     */
    public function getUsers ()
    {
        $user = JWTAuth::parseToken()->authenticate();

        $roles = Role::get(['name', 'id']);

        $role = $user->role->name !== Role::DRIVER_NAME
            ? $roles->where('name', 'driver')->first()->id
            : $roles->where('name', 'admin')->first()->id;


        $users = User::chatUsers($role, $user->company_id)->get();

        return new UsersCollection($users);
    }

    /**
     * Get list of rooms for chat
     *
     * @return RoomCollection
     */
    public function getRoomList ()
    {
        $user = JWTAuth::parseToken()->authenticate();

        return new RoomCollection($user->rooms()->with('users')->get());
    }

    /**
     * Get message history of chat room
     *
     * @param ChatRoom $room
     * @return HistoryCollection
     */
    public function getMessageHistory (ChatRoom $room)
    {
        $user = JWTAuth::parseToken()->authenticate();

        $history = Message::history($room->id)->get();

        return new HistoryCollection($history);
    }

    /**
     * Add new message
     *
     * @param array $attributes
     * @return MessageResource
     */
    public function storeMessage (array $attributes)
    {
        $user = JWTAuth::parseToken()->authenticate();

        $message = new Message();

        $attributes['sender_id'] = $user->id;
        $attributes['type'] = $message::TYPES['message'];

        $message->fill($attributes);

        $message->save();

        return new MessageResource($message);
    }

    /**
     * Add new message with file
     *
     * @param array $attributes
     * @return MessageResource
     */
    public function storeFile (array $attributes)
    {
        $user = JWTAuth::parseToken()->authenticate();

        $attributes['sender_id'] = $user->id;

        $message = new Message();

        $message->createWithFile($attributes);

        return new MessageResource($message);
    }

    /**
     * Create new chat room
     *
     * @param int $reciever
     */
    public function setChatRoom (int $reciever)
    {
        $user = JWTAuth::parseToken()->authenticate();

        $reciever = User::find($reciever);

        $room = ChatRoom::create();

        $room->users()->attach([$user->id, $reciever->id]);

        $room->users()->get()->each(static function (User $user) use ($room) {
            event(new RoomCreated($user, $room));
        });
    }
}
