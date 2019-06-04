<?php

namespace App\Http\Controllers\API\Admin;

use App\{Http\Controllers\Controller,
    Http\Requests\API\GodsEye\SetDriverWaypointsRequest,
    Order,
    Services\GodsEyeService,
    User};
use Illuminate\Http\Request;


class GodsEyeController extends Controller
{
    private $service;

    public function __construct (GodsEyeService $service)
    {
        $this->service = $service;
    }

    /**
     * Get list of orders for Gods Eye
     *
     * @param Request $request
     * @param Order $orders
     * @return void
     */
    public function getGodsEyeOrders (Request $request)
    {
        $result = $this->service->orders($request);

        return response($result)->withHeaders([
            'X-Total-Count' => $result->total(),
            'Access-Control-Expose-Headers' => 'X-Total-Count'
        ]);
    }

    /**
     * Get list of drivers for Gods Eye
     *
     * @param Request $request
     * @param User $users
     * @return void
     */
    public function getGodsEyeDrivers ()
    {
        $result = $this->service->drivers();

        return response($result)->withHeaders([
            'X-Total-Count' => $result->total(),
            'Access-Control-Expose-Headers' => 'X-Total-Count'
        ]);
    }

    /**
     * Assign drivers to orders
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function assignDriversToOrder (Request $request)
    {
        $this->service->assign($request->all());

        return $this->isSuccess();
    }

    /**
     * Set driver delivery waypoints
     *
     * @param SetDriverWaypointsRequest $request
     * @param User $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function setDriverWaypoints (SetDriverWaypointsRequest $request, User $user)
    {
        $this->service->setDriverWaypoints($user, $request->all());

        return $this->isSuccess();
    }
}
