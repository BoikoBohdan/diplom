<?php

namespace App\Observers\API\Users;

use App\User;

class UserObserver
{
    /**
     * Handle the orders locations "deleting" event.
     *
     * @param User $User
     * @return void
     */
    public function deleting (User $user)
    {
        if ($user->driver) {
            $user->driver->delete();
        }

        if ($user->workLocations->count() > 0) {
            $user->workLocations->each(static function ($location) {
                return $location->delete();
            });
        }
    }
}
