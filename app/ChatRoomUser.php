<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChatRoomUser extends Model
{
    /**
     * @var string
     */
    protected $table = 'chat_room_user';

    /**
     * Fillable fields
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'chat_room_id'
    ];

    /**
     * Chat room
     *
     * @return BelongsTo
     */
    public function room ()
    {
        return $this->belongsTo(ChatRoom::class, 'chat_room_id');
    }

    /**
     * Related user
     *
     * @return BelongsTo
     */
    public function user ()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

}
