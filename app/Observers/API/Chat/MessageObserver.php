<?php

namespace App\Observers\API\Chat;

use App\{Events\API\Chat\MessageSent, Message};

class MessageObserver
{
    /**
     * Handle the message "created" event.
     *
     * @param Message $message
     * @return void
     */
    public function created (Message $message)
    {
        broadcast(new MessageSent($message))->toOthers();
    }

    /**
     * Handle the message "creating" event.
     *
     * @param Message $message
     * @return void
     */
    public function creating (Message $message)
    {
        //
    }

    /**
     * Handle the message "updated" event.
     *
     * @param Message $message
     * @return void
     */
    public function updated (Message $message)
    {
        //
    }

    /**
     * Handle the message "deleted" event.
     *
     * @param Message $message
     * @return void
     */
    public function deleted (Message $message)
    {
        //
    }

    /**
     * Handle the message "restored" event.
     *
     * @param Message $message
     * @return void
     */
    public function restored (Message $message)
    {
        //
    }

    /**
     * Handle the message "force deleted" event.
     *
     * @param Message $message
     * @return void
     */
    public function forceDeleted (Message $message)
    {
        //
    }
}
