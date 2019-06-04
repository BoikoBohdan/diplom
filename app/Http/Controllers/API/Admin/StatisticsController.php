<?php

namespace App\Http\Controllers\API\Admin;

use App\{Driver, Http\Controllers\Controller, Order};
use Illuminate\Http\Response;

class StatisticsController extends Controller
{
    /**
     * Get statistics for dashboard page
     *
     * @param Order $orders
     * @param Driver $drivers
     * @return Response
     */
    public function __invoke (Order $orders, Driver $drivers)
    {
        return response([
            'orders' => $orders->statistics(),
            'orders_delivery' => $orders->getDeliveryStatistics(),
            'drivers' => $drivers->statistics()
        ]);
    }
}
