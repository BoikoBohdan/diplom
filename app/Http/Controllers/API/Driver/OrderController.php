<?php

namespace App\Http\Controllers\API\Driver;

use App\{Http\Controllers\Controller,
    Http\Requests\API\Orders\SetOrderStatusRequest,
    Order,
    Services\Mobile\OrderMobileService};
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Requests\API\Mobile\CancelReasonRequest;

class OrderController extends Controller
{
    public $service;

    public function __construct (OrderMobileService $service)
    {
        $this->service = $service;
    }

    /**
     * @OA\Get (
     *     path="/driver/order",
     *     tags={"Mobile App"},
     *     summary="Get orders list",
     *     operationId="ordersList",
     *     @OA\Response (
     *         response="200",
     *         description="Orders list",
     *         @OA\JsonContent (
     *             @OA\Items (
     *                 @OA\Property (
     *                     property="orders",
     *                     type="array",
     *                     @OA\Items (
     *                           @OA\Property (
     *                              property="id",
     *                              type="integer",
     *                              example="1"
     *                           ),
     *                           @OA\Property (
     *                               property="collect",
     *                               type="string",
     *                               example="e058c779-5141-378e-8c55-8d065e5ec3e6"
     *                           ),
     *                           @OA\Property (
     *                               property="payment_type",
     *                               type="integer",
     *                               example="1"
     *                          ),
     *                           @OA\Property (
     *                               property="order_note",
     *                               type="string",
     *                               example="Et saepe impedit quia repellendus velit"
     *                           ),
     *                           @OA\Property (
     *                               property="total",
     *                               type="float",
     *                               example="1,15"
     *                           ),
     *                           @OA\Property (
     *                               property="pickup_address",
     *                               type="string",
     *                               example="string"
     *                           ),
     *                           @OA\Property (
     *                               property="pickup_city",
     *                               type="string",
     *                               example="string"
     *                           ),
     *                           @OA\Property (
     *                               property="pickup_country_code",
     *                               type="string",
     *                               example="string"
     *                           ),
     *                           @OA\Property (
     *                               property="pickup_date",
     *                               type="date",
     *                               example="2019-05-10"
     *                           ),
     *                           @OA\Property (
     *                               property="pickup_time_from",
     *                               type="time",
     *                               example="12:40:44"
     *                           ),
     *                           @OA\Property (
     *                               property="pickup_time_to",
     *                               type="time",
     *                               example="20:10:34"
     *                           ),
     *                            @OA\Property (
     *                               property="dropoff_address",
     *                               type="string",
     *                               example="AS, New Quinten, Hallwylstrasse 43, 8004 Zürich"
     *                           ),
     *                           @OA\Property (
     *                               property="dropoff_city",
     *                               type="string",
     *                               example="Zürich"
     *                           ),
     *                           @OA\Property (
     *                               property="dropoff_country_code",
     *                               type="string",
     *                               example="4561"
     *                           ),
     *                           @OA\Property (
     *                               property="dropoff_date",
     *                               type="date",
     *                               example="2019-05-10"
     *                           ),
     *                           @OA\Property (
     *                               property="dropoff_time_from",
     *                               type="time",
     *                               example="12:10:17"
     *                           ),
     *                           @OA\Property (
     *                               property="dropoff_time_to",
     *                               type="time",
     *                               example="15:20:54"
     *                           ),
     *                            @OA\Property (
     *                               property="restaurant_id",
     *                               type="integer",
     *                               example="1"
     *                           ),
     *                           @OA\Property (
     *                               property="restaurant_name",
     *                               type="string",
     *                               example="restaurant"
     *                           ),
     *                           @OA\Property (
     *                               property="restaurant_phone",
     *                               type="string",
     *                               example="+852151546"
     *                           ),
     *                           @OA\Property (
     *                               property="restaurant_address",
     *                               type="string",
     *                               example="AS, New Quinten, Hallwylstrasse 43, 8004 Zürich"
     *                           ),
     *                           @OA\Property (
     *                               property="restaurant_city",
     *                               type="string",
     *                               example="Zürich"
     *                           ),
     *                           @OA\Property (
     *                               property="restaurant_country_code",
     *                               type="string",
     *                               example="8004"
     *                           ),
     *                           @OA\Property (
     *                               property="restaurant_postcode",
     *                               type="string",
     *                               example="8004"
     *                           ),
     *                           @OA\Property (
     *                               property="restaurant_note",
     *                               type="string",
     *                               example="note"
     *                           ),
     *                           @OA\Property (
     *                               property="products",
     *                               type="array",
     *                               @OA\Items (
     *                                   @OA\Property (
     *                                       property="name",
     *                                       type="string",
     *                                       example="burger"
     *                                   ),
     *                                   @OA\Property (
     *                                       property="quantity",
     *                                       type="integer",
     *                                       example="2"
     *                                   ),
     *                                   @OA\Property (
     *                                       property="note",
     *                                       type="string",
     *                                       example="with cheese"
     *                                   ),
     *                                   @OA\Property (
     *                                       property="fee",
     *                                       type="integer",
     *                                       example="25"
     *                                   ),
     *                                   @OA\Property (
     *                                       property="image",
     *                                       type="string",
     *                                       example="image/example.jpg"
     *                                   ),
     *                                    @OA\Property (
     *                                        property="customer_id",
     *                                        type="integer",
     *                                        example="1"
     *                                    ),
     *                                 )
     *                               ),
     *                          @OA\Property (
     *                             property="status",
     *                             type="integer",
     *                             example="1"
     *                          ),
     *                          @OA\Property (
     *                             property="status_title",
     *                             type="string",
     *                             example="assigned"
     *                          ),
     *                          @OA\Property (
     *                             property="customer",
     *                             type="array",
     *                             @OA\Items (
     *                                   @OA\Property (
     *                                       property="id",
     *                                       type="integer",
     *                                       example="1"
     *                                   ),
     *                                   @OA\Property (
     *                                       property="name",
     *                                       type="string",
     *                                       example="example"
     *                                   ),
     *                             )
     *                          ),
     *                          @OA\Property (
     *                             property="ratings",
     *                             type="array",
     *                             @OA\Items (
     *                                   @OA\Property (
     *                                       property="id",
     *                                       type="integer",
     *                                       example="1"
     *                                   ),
     *                                   @OA\Property (
     *                                       property="score",
     *                                       type="integer",
     *                                       example="233"
     *                                   ),
     *                                   @OA\Property (
     *                                       property="message",
     *                                       type="string",
     *                                       example="example"
     *                                   ),
     *                                   @OA\Property (
     *                                       property="ratingable_type",
     *                                       type="string",
     *                                       example="app/model"
     *                                   ),
     *                                   @OA\Property (
     *                                       property="ratingable_id",
     *                                       type="integer",
     *                                       example="1"
     *                                   ),
     *                             )
     *                          ),
     *                          @OA\Property (
     *                               property="drivers",
     *                               type="array",
     *                               @OA\Items (
     *                                   @OA\Property (
     *                                       property="user_id",
     *                                       type="integer",
     *                                       example="1"
     *                                   ),
     *                                   @OA\Property (
     *                                       property="full_name",
     *                                       type="string",
     *                                       example="Example Example"
     *                                   ),
     *                                   @OA\Property (
     *                                       property="image",
     *                                       type="string",
     *                                       example="image/example.jpg"
     *                                   ),
     *                                  )
     *                             ),
     *                     )
     *                  ),
     *                     @OA\Property (
     *                     property="waypoints",
     *                     type="array",
     *                        @OA\Items (
     *                              @OA\Property (
     *                                 property="id",
     *                                 type="integer",
     *                                 example="1"
     *                              ),
     *                              @OA\Property (
     *                                 property="order_id",
     *                                 type="integer",
     *                                 example="1"
     *                              ),
     *                              @OA\Property (
     *                                 property="type",
     *                                 type="string",
     *                                 example="A"
     *                              ),
     *                         )
     *                    )
     *             )
     *         )
     *     ),
     *     @OA\Response (
     *         response="401",
     *         description="Authentication error",
     *         @OA\JsonContent (
     *             @OA\Property (
     *                 property="message",
     *                 type="string",
     *                 description="Message for API authentication error",
     *                 example="token_expired|token_invalid|token_required"
     *             )
     *         )
     *     ),
     *      security={{"bearerAuth":{}}},
     * )
     */
    public function index ()
    {
        $user = JWTAuth::parseToken()->authenticate();

        return $this->service->getDriversOrders($user->driver);
    }

    /**
     * @OA\Get (
     *     path="/driver/order/{order}",
     *     tags={"Mobile App"},
     *     summary="Get order details",
     *     operationId="orderDetails",
     *     @OA\Parameter (
     *         required=true,
     *         parameter="order",
     *         name="order",
     *         in="path",
     *         @OA\Schema (
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response (
     *         response="200",
     *         description="Order details",
     *         @OA\JsonContent (
     *             @OA\Property (
     *                              property="id",
     *                              type="integer",
     *                              example="1"
     *                           ),
     *                           @OA\Property (
     *                               property="collect",
     *                               type="string",
     *                               example="e058c779-5141-378e-8c55-8d065e5ec3e6"
     *                           ),
     *                           @OA\Property (
     *                               property="payment_type",
     *                               type="integer",
     *                               example="1"
     *                          ),
     *                           @OA\Property (
     *                               property="order_note",
     *                               type="string",
     *                               example="Et saepe impedit quia repellendus velit"
     *                           ),
     *                           @OA\Property (
     *                               property="total",
     *                               type="float",
     *                               example="1,15"
     *                           ),
     *                           @OA\Property (
     *                               property="pickup_address",
     *                               type="string",
     *                               example="string"
     *                           ),
     *                           @OA\Property (
     *                               property="pickup_city",
     *                               type="string",
     *                               example="string"
     *                           ),
     *                           @OA\Property (
     *                               property="pickup_country_code",
     *                               type="string",
     *                               example="string"
     *                           ),
     *                           @OA\Property (
     *                               property="pickup_date",
     *                               type="date",
     *                               example="2019-05-10"
     *                           ),
     *                           @OA\Property (
     *                               property="pickup_time_from",
     *                               type="time",
     *                               example="12:40:44"
     *                           ),
     *                           @OA\Property (
     *                               property="pickup_time_to",
     *                               type="time",
     *                               example="20:10:34"
     *                           ),
     *                            @OA\Property (
     *                               property="dropoff_address",
     *                               type="string",
     *                               example="AS, New Quinten, Hallwylstrasse 43, 8004 Zürich"
     *                           ),
     *                           @OA\Property (
     *                               property="dropoff_city",
     *                               type="string",
     *                               example="Zürich"
     *                           ),
     *                           @OA\Property (
     *                               property="dropoff_country_code",
     *                               type="string",
     *                               example="4561"
     *                           ),
     *                           @OA\Property (
     *                               property="dropoff_date",
     *                               type="date",
     *                               example="2019-05-10"
     *                           ),
     *                           @OA\Property (
     *                               property="dropoff_time_from",
     *                               type="time",
     *                               example="12:10:17"
     *                           ),
     *                           @OA\Property (
     *                               property="dropoff_time_to",
     *                               type="time",
     *                               example="15:20:54"
     *                           ),
     *                            @OA\Property (
     *                               property="restaurant_id",
     *                               type="integer",
     *                               example="1"
     *                           ),
     *                           @OA\Property (
     *                               property="restaurant_name",
     *                               type="string",
     *                               example="restaurant"
     *                           ),
     *                           @OA\Property (
     *                               property="restaurant_phone",
     *                               type="string",
     *                               example="+852151546"
     *                           ),
     *                           @OA\Property (
     *                               property="restaurant_address",
     *                               type="string",
     *                               example="AS, New Quinten, Hallwylstrasse 43, 8004 Zürich"
     *                           ),
     *                           @OA\Property (
     *                               property="restaurant_city",
     *                               type="string",
     *                               example="Zürich"
     *                           ),
     *                           @OA\Property (
     *                               property="restaurant_country_code",
     *                               type="string",
     *                               example="8004"
     *                           ),
     *                           @OA\Property (
     *                               property="restaurant_postcode",
     *                               type="string",
     *                               example="8004"
     *                           ),
     *                           @OA\Property (
     *                               property="restaurant_note",
     *                               type="string",
     *                               example="note"
     *                           ),
     *                           @OA\Property (
     *                               property="products",
     *                               type="array",
     *                               @OA\Items (
     *                                   @OA\Property (
     *                                       property="name",
     *                                       type="string",
     *                                       example="burger"
     *                                   ),
     *                                   @OA\Property (
     *                                       property="quantity",
     *                                       type="integer",
     *                                       example="2"
     *                                   ),
     *                                   @OA\Property (
     *                                       property="note",
     *                                       type="string",
     *                                       example="with cheese"
     *                                   ),
     *                                   @OA\Property (
     *                                       property="fee",
     *                                       type="integer",
     *                                       example="25"
     *                                   ),
     *                                   @OA\Property (
     *                                       property="image",
     *                                       type="string",
     *                                       example="image/example.jpg"
     *                                   ),
     *                                    @OA\Property (
     *                                        property="customer_id",
     *                                        type="integer",
     *                                        example="1"
     *                                    ),
     *                                 )
     *                               ),
     *                          @OA\Property (
     *                             property="status",
     *                             type="integer",
     *                             example="1"
     *                          ),
     *                          @OA\Property (
     *                             property="status_title",
     *                             type="string",
     *                             example="assigned"
     *                          ),
     *                          @OA\Property (
     *                             property="customer",
     *                             type="array",
     *                             @OA\Items (
     *                                   @OA\Property (
     *                                       property="id",
     *                                       type="integer",
     *                                       example="1"
     *                                   ),
     *                                   @OA\Property (
     *                                       property="name",
     *                                       type="string",
     *                                       example="example"
     *                                   ),
     *                             )
     *                          ),
     *                          @OA\Property (
     *                             property="ratings",
     *                             type="array",
     *                             @OA\Items (
     *                                   @OA\Property (
     *                                       property="id",
     *                                       type="integer",
     *                                       example="1"
     *                                   ),
     *                                   @OA\Property (
     *                                       property="score",
     *                                       type="integer",
     *                                       example="233"
     *                                   ),
     *                                   @OA\Property (
     *                                       property="message",
     *                                       type="string",
     *                                       example="example"
     *                                   ),
     *                                   @OA\Property (
     *                                       property="ratingable_type",
     *                                       type="string",
     *                                       example="app/model"
     *                                   ),
     *                                   @OA\Property (
     *                                       property="ratingable_id",
     *                                       type="integer",
     *                                       example="1"
     *                                   ),
     *                             )
     *                          ),
     *                          @OA\Property (
     *                               property="drivers",
     *                               type="array",
     *                               @OA\Items (
     *                                   @OA\Property (
     *                                       property="user_id",
     *                                       type="integer",
     *                                       example="1"
     *                                   ),
     *                                   @OA\Property (
     *                                       property="full_name",
     *                                       type="string",
     *                                       example="Example Example"
     *                                   ),
     *                                   @OA\Property (
     *                                       property="image",
     *                                       type="string",
     *                                       example="image/example.jpg"
     *                                   ),
     *                                  )
     *                             ),
     *     ),
     *     @OA\Response (
     *         response="401",
     *         description="Authentication error",
     *         @OA\JsonContent (
     *             @OA\Property (
     *                 property="message",
     *                 type="string",
     *                 description="Message for API authentication error",
     *                 example="token_expired|token_invalid|token_required"
     *             )
     *         )
     *     ),
     *     @OA\Response (
     *         response="404",
     *         description="Order not found error",
     *         @OA\JsonContent (
     *             @OA\Property (
     *                 property="message",
     *                 type="string",
     *                 example="Order not found"
     *             )
     *         )
     *     ),
     * ),
     * ),
     *     security={{"bearerAuth":{}}}
     * )
     */
    public function show (Order $order)
    {
        return $this->service->show($order);
    }

    /**
     * @OA\Patch (
     *     path="/driver/order/{order}/set-status",
     *     tags={"Mobile App"},
     *     summary="Set order status",
     *     @OA\Parameter (
     *         required=true,
     *         parameter="order",
     *         name="order",
     *         in="path",
     *         @OA\Schema (
     *             type="integer"
     *         )
     *     ),
     *      @OA\RequestBody(
     *         required=true,
     *      @OA\MediaType(
     *           mediaType="application/json",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(
     *                     property="status",
     *                     type="integer",
     *                     example="3"
     *                 ),
     *              )
     *          )
     *      ),
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\Response (
     *         response="200",
     *         description="Message",
     *         @OA\JsonContent (
     *             @OA\Property (
     *                 property="message",
     *                 type="string"
     *             )
     *     ),
     *     @OA\Response (
     *         response="401",
     *         description="Authentication error",
     *         @OA\JsonContent (
     *             @OA\Property (
     *                 property="message",
     *                 type="string",
     *                 description="Message for API authentication error",
     *                 example="token_expired|token_invalid|token_required"
     *             )
     *         )
     *     ),
     *     @OA\Response (
     *         response="404",
     *         description="Order not found error",
     *         @OA\JsonContent (
     *             @OA\Property (
     *                 property="message",
     *                 type="string",
     *                 example="Order not found"
     *             )
     *         )
     *     ),
     *  ),
     *  security={{"bearerAuth":{}}}
     * )
     */
    public function setStatus (SetOrderStatusRequest $request, Order $order)
    {
        $this->service->setStatus($order, $request->status);

        return $this->isSuccess();
    }

    /**
     * @OA\POST (
     *     path="/driver/order/{order}/cancel-request",
     *     tags={"Mobile App"},
     *     summary="Set order cancel reason",
     *     @OA\Parameter (
     *         required=true,
     *         parameter="order",
     *         name="order",
     *         in="path",
     *         @OA\Schema (
     *             type="integer"
     *         )
     *     ),
     *      @OA\RequestBody(
     *         required=true,
     *      @OA\MediaType(
     *           mediaType="application/json",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(
     *                     property="drivers_reason",
     *                     type="string",
     *                 ),
     *              )
     *          )
     *      ),
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\Response (
     *         response="200",
     *         description="Message",
     *         @OA\JsonContent (
     *             @OA\Property (
     *                 property="message",
     *                 type="string"
     *             )
     *     ),
     *     @OA\Response (
     *         response="401",
     *         description="Authentication error",
     *         @OA\JsonContent (
     *             @OA\Property (
     *                 property="message",
     *                 type="string",
     *                 description="Message for API authentication error",
     *                 example="token_expired|token_invalid|token_required"
     *             )
     *         )
     *     ),
     *     @OA\Response (
     *         response="404",
     *         description="Order not found error",
     *         @OA\JsonContent (
     *             @OA\Property (
     *                 property="error",
     *                 type="string",
     *                 example="Model not found"
     *             )
     *         )
     *     ),
     *     @OA\Response (
     *         response="422",
     *         description="Cancel reason relation already exists",
     *         @OA\JsonContent (
     *             @OA\Property (
     *                 property="error",
     *                 type="string",
     *                 example="Cancel reason relation already exists"
     *             )
     *         )
     *     ),
     *  ),
     *  security={{"bearerAuth":{}}}
     * )
     */
    public function setCancelOrderReason(CancelReasonRequest $request, Order $order)
    {
        $this->service->setCancelOrderReason($request->all(), $order);

        return $this->isSuccess();
    }
}
