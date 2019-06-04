<?php

namespace App\Rules\API;

use App\User;
use Illuminate\Contracts\Validation\Rule;

class CanSetOrderWaypoint implements Rule
{
    /**
     * @var User
     */
    protected $user;

    /**
     * @var array
     */
    protected $waypoints;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct (User $user, array $waypoints)
    {
        $this->user = $user;
        $this->waypoints = $waypoints;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    public function passes ($attribute, $value)
    {
        $orders = $this->user->driver->orders;
        $waypoints = collect($this->waypoints['waypoints']);

        $filtered = $waypoints->filter(static function ($waypoint) use ($orders) {
            return $orders->contains(static function ($order) use ($waypoint) {
                return $waypoint['order_id'] == $order->id;
            });
        })->count();

        return $filtered === $waypoints->count();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message ()
    {
        return 'Driver doesn`t have such order';
    }
}
