<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ChatRoom extends Model
{
    /**
     * Fillable fields
     *
     * @var array
     */
    protected $fillable = [
        'key'
    ];

    /**
     * Users present in room
     *
     * @return BelongsToMany
     */
    public function users ()
    {
        return $this->belongsToMany(User::class);
    }

    /**
     * Messages in the room
     *
     * @return HasMany
     */
    public function messages ()
    {
        return $this->hasMany(Message::class);
    }
}
