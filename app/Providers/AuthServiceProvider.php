<?php

namespace App\Providers;

use App\ChatRoom;
use App\DriverOrder;
use App\Order;
use App\Policies\API\Admin\AssignOrderPolicy;
use App\Policies\API\Admin\ChatPolicy;
use App\Policies\API\Admin\OrderPolicy;
use App\Policies\API\Admin\ShiftPolicy;
use App\Policies\API\Admin\UserPolicy;
use App\Shift;
use App\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        User::class => UserPolicy::class,
        Order::class => OrderPolicy::class,
        DriverOrder::class => AssignOrderPolicy::class,
        Shift::class => ShiftPolicy::class,
        ChatRoom::class => ChatPolicy::class
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot ()
    {
        $this->registerPolicies();
    }
}
