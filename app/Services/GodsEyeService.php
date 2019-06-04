<?php

namespace App\Services;

use App\{Driver,
    DriverOrder,
    Http\Resources\GodsEye\Drivers\DriversCollection,
    Http\Resources\GodsEye\Orders\OrdersCollection,
    Order,
    OrderGroup,
    User,
    Waypoint};
use Illuminate\{Support\Facades\DB};

class GodsEyeService extends BasicService
{
    public const PER_PAGE = 10;

    /**
     * @param $request
     * @return OrdersCollection
     */
    public function orders ($request)
    {
        return new OrdersCollection(
            Order::getGodsEyeOrders($request)->paginate(self::PER_PAGE)
        );
    }

    /**
     * @return DriversCollection
     */
    public function drivers ()
    {
        return new DriversCollection(
            Driver::getGodsEyeDrivers()->paginate(self::PER_PAGE)
        );
    }

    /**
     * @param array $attributes
     */
    public function assign (array $attributes)
    {
        DriverOrder::add($attributes);
    }

    /**
     * @param User $user
     * @param array $attributes
     */
    public function setDriverWaypoints (User $user, array $attributes)
    {
        $waypointsCollection = collect($attributes['waypoints']);

        DB::transaction(static function () use ($waypointsCollection, $user) {

            $waypointsCollection->unique('order_id')->each(static function ($attributes) {
                Waypoint::where('order_id', $attributes['order_id'])->delete(); // Delete old waypoints
            });

            $waypoints = $waypointsCollection->map(static function ($attributes) {
                return Waypoint::create($attributes); // Create new waypoints
            });

            $group = OrderGroup::create(); // Create new group

            $waypoints->each(static function (Waypoint $waypoint) use ($group) {
                $waypoint->order->setGroup($group->id); // Attach order to group
            });
        });
    }
}
