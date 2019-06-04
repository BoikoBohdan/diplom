<?php

namespace App\Components\Traits;

use Illuminate\Support\Collection;

trait Broadcastable
{
    /**
     * Check if updated model will be sent to broadcast event
     *
     * @param Collection $changes
     * @param array $broadcastableAttributes
     * @return boolean
     */
    public function isBroadcastable (Collection $changes, array $broadcastableAttributes)
    {
        return $changes->keys()->filter(static function ($change) use ($broadcastableAttributes) {
                return count(array_keys($broadcastableAttributes, $change)) > 0;
            })->count() > 0;
    }
}
