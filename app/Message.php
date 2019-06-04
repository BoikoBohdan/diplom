<?php

namespace App;

use App\Components\Traits\Storage\Files as StorageTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use StorageTrait;

    public const TYPES = [
        'message' => 1,
        'image' => 2,
        'file' => 3
    ];
    /**
     * Fillable fields
     *
     * @var array
     */
    protected $fillable = [
        'sender_id', 'reciever_id', 'message', 'chat_room_id', 'type'
    ];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function sender ()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function reciever ()
    {
        return $this->belongsTo(User::class, 'reciever_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function room ()
    {
        return $this->belongsTo(ChatRoom::class, 'chat_room_id');
    }

    /**
     * @param Builder $query
     * @param int $sender
     * @return Builder
     */
    public function scopeBySender (Builder $query, int $sender)
    {
        return $query->where('sender_id', $sender);
    }

    /**
     * @param Builder $query
     * @param int $reciever
     * @return Builder
     */
    public function scopeByReciever (Builder $query, int $reciever)
    {
        return $query->where('reciever_id', $reciever);
    }

    /**
     * @param Builder $query
     * @param int $room
     * @return Builder
     */
    public function scopeHistory (Builder $query, int $room)
    {
        return $query
            ->where('chat_room_id', $room)
            ->select('id', 'reciever_id', 'sender_id', 'message', 'created_at', 'type')
            ->groupBy('created_at')
            ->orderBy('created_at', 'ASC');
    }

    /**
     * @param array $attributes
     * @return $this
     * @throws \Illuminate\Validation\ValidationException
     */
    public function createWithFile (array $attributes)
    {
        $attributes['message'] = self::setChatFile($attributes['file'], 'chat-files');

        $attributes['type'] = $this->setType(str_after($attributes['message'], '.'));

        $this->fill($attributes);

        $this->save();

        return $this;
    }

    /**
     * Set message type attribute
     *
     * @param string $mime
     * @return integer
     */
    public function setType (string $mime)
    {
        $result = self::TYPES['message'];

        if ($this->checkMimeType(self::imageMimes(), $mime)) {
            $result = self::TYPES['image'];
        }

        if ($this->checkMimeType(self::fileMimes(), $mime)) {
            $result = self::TYPES['file'];
        }

        return $this->attributes['type'] = $result;
    }
}
