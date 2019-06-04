<?php

namespace App\Http\Controllers\API\Admin;

use App\{Http\Controllers\Controller,
    Http\Requests\API\Orders\CreateOrder,
    Http\Requests\API\Orders\SetOrderStatusRequest,
    Http\Requests\API\Orders\UpdateOrder,
    Order,
    Services\OrderService};
use Exception;
use Illuminate\{Http\Request, Http\Response};

class OrdersController extends Controller
{
    private $order;

    public function __construct (OrderService $order)
    {
        $this->order = $order;

        $this->authorizeResource(Order::class, 'order');
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @param Order $orders
     * @return Response
     */
    public function index (Request $request, Order $orders): Response
    {
        $result = $this->order->all($request, $orders);

        return response($result)->withHeaders([
            'X-Total-Count' => $result->total(),
            'Access-Control-Expose-Headers' => 'X-Total-Count'
        ]);
    }

    /**
     * @OA\Post(path="/admin/orders",
     *   tags={"Orders"},
     *   description="Add new order",
     *   summary="Create new order",
     *     @OA\RequestBody(
     *         required=true,
     *      @OA\MediaType(
     *           mediaType="application/json",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(
     *                     property="GroupGuid",
     *                     type="string",
     *                     example="D2610E5E-9541-49FF-B330-E217F477F2D0"
     *                 ),
     *                  @OA\Property(
     *                     property="Reference",
     *                     type="string",
     *                     example="15469432494f1"
     *                 ),
     *                  @OA\Property(
     *                     property="PickupDate",
     *                     type="string",
     *                     example="2019-01-08"
     *                 ),
     *                  @OA\Property(
     *                     property="DropoffDate",
     *                     type="string",
     *                     example="2019-01-08"
     *                 ),
     *                  @OA\Property(
     *                     property="PickupTimeFrom",
     *                     type="string",
     *                     example="10:57:29Z"
     *                 ),
     *                  @OA\Property(
     *                     property="PickupTimeTo",
     *                     type="string",
     *                     example="10:57:29Z"
     *                 ),
     *                  @OA\Property(
     *                     property="DropoffTimeFrom",
     *                     type="string",
     *                     example="11:12:29Z"
     *                 ),
     *                  @OA\Property(
     *                     property="DropoffTimeTo",
     *                     type="string",
     *                     example="11:12:29Z"
     *                 ),
     *                  @OA\Property(
     *                     property="EnforceSignature",
     *                     type="boolean",
     *                     example="true"
     *                 ),
     *                  @OA\Property(
     *                     property="PickupNotes",
     *                     type="string",
     *                     example="Zeit ab: 11:57"
     *                 ),
     *                  @OA\Property(
     *                     property="DropoffNotes",
     *                     type="string",
     *                     example="Zeit um: 12:12, Floor: 1"
     *                 ),
     *                  @OA\Property(
     *                     property="EstimatedTimeLoading",
     *                     type="integer",
     *                     example="5"
     *                 ),
     *                  @OA\Property(
     *                     property="EstimatedTimeDroppingOff",
     *                     type="integer",
     *                     example="0"
     *                 ),
     *                  @OA\Property(
     *                     property="EstimatedBreakAfterDropoff",
     *                     type="integer",
     *                     example="0"
     *                 ),
     *                  @OA\Property(
     *                     property="LoadType",
     *                     type="integer",
     *                     example="0"
     *                 ),
     *                  @OA\Property(
     *                     property="Fee",
     *                     type="string",
     *                     example="66.90"
     *                 ),
     *                  @OA\Property(
     *                     property="Notes",
     *                     type="string",
     *                     example="some text"
     *                 ),
     *                  @OA\Property(
     *                     property="PaymentInfo",
     *                     type="string",
     *                     example="some text"
     *                 ),
     *                  @OA\Property(
     *                     property="PaymentType",
     *                     type="integer",
     *                     example="1"
     *                 ),
     *                  @OA\Property(
     *                     property="CustomerInfo",
     *                     type="string",
     *                     example="some info"
     *                 ),
     *                  @OA\Property(
     *                     property="ASAP",
     *                     type="boolean"
     *                 ),
     *                  @OA\Property(
     *                     property="ShipmentType",
     *                     type="integer",
     *                     example="11"
     *                 ),
     *                  @OA\Property(
     *                     property="Pickup",
     *                     type="object",
     *                          @OA\Property(
     *                              property="Reference",
     *                              type="string",
     *                              example="2578"
     *                           ),
     *                          @OA\Property(
     *                              property="Name",
     *                              type="string",
     *                              example="Subway Winterthur Neumarkt"
     *                           ),
     *                          @OA\Property(
     *                              property="Phone",
     *                              type="string",
     *                              example="0848 74 99 21"
     *                           ),
     *                          @OA\Property(
     *                              property="City",
     *                              type="string",
     *                              example="Winterthur"
     *                           ),
     *                          @OA\Property(
     *                              property="CountryCode",
     *                              type="string",
     *                              example="CH"
     *                           ),
     *                          @OA\Property(
     *                              property="Postcode",
     *                              type="string",
     *                              example="8400"
     *                           ),
     *                          @OA\Property(
     *                              property="Streetaddress",
     *                              type="string",
     *                              example="Neumarkt 17"
     *                           ),
     *                          @OA\Property(
     *                              property="ContactName",
     *                              type="string",
     *                              example="Julian Lechner - Andreschewski (SW Operations AG)"
     *                           ),
     *                          @OA\Property(
     *                              property="Note",
     *                              type="string",
     *                              example="some notes"
     *                           ),
     *
     *                 ),
     *                  @OA\Property(
     *                     property="Dropoff",
     *                     type="object",
     *                          @OA\Property(
     *                              property="Reference",
     *                              type="string",
     *                              example="null"
     *                           ),
     *                          @OA\Property(
     *                              property="Name",
     *                              type="string",
     *                              example="Init7"
     *                           ),
     *                          @OA\Property(
     *                              property="Phone",
     *                              type="string",
     *                              example="0787630111"
     *                           ),
     *                          @OA\Property(
     *                              property="City",
     *                              type="string",
     *                              example="Winterthur"
     *                           ),
     *                          @OA\Property(
     *                              property="CountryCode",
     *                              type="string",
     *                              example="CH"
     *                           ),
     *                          @OA\Property(
     *                              property="Postcode",
     *                              type="string",
     *                              example="8406"
     *                           ),
     *                          @OA\Property(
     *                              property="Streetaddress",
     *                              type="string",
     *                              example="Technoparkstrasse 5"
     *                           ),
     *                          @OA\Property(
     *                              property="ContactName",
     *                              type="string",
     *                              example="Khadija Mojtahid"
     *                           ),
     *                          @OA\Property(
     *                              property="Note",
     *                              type="string",
     *                              example="some notes"
     *                           ),
     *                 ),
     *                   @OA\Property(
     *                     property="Products",
     *                     type="object",
     *                          @OA\Property(
     *                              property="Reference",
     *                              type="string",
     *                              example="623ceb5c6b92db5b6f9966ec96935dde"
     *                           ),
     *                          @OA\Property(
     *                              property="Name",
     *                              type="string",
     *                              example="Chicken Teriyaki Sandwich 30cm Getoastet, Cheese Oregano Brot, Scheibenkäse, Philadelphia, Extra Cheddar, Cheddar, Salat, Tomaten, Gurken, Oliven, Honey Mustard Sauce, Barbecuesauce"
     *                           ),
     *                          @OA\Property(
     *                              property="UnitType",
     *                              type="integer",
     *                              example="9"
     *                           ),
     *                          @OA\Property(
     *                              property="City",
     *                              type="string",
     *                              example="Winterthur"
     *                           ),
     *                          @OA\Property(
     *                              property="Quantity",
     *                              type="integer",
     *                              example="1"
     *                           ),
     *                          @OA\Property(
     *                              property="Note",
     *                              type="string",
     *                              example="some notes"
     *                           ),
     *                          @OA\Property(
     *                              property="Fee",
     *                              type="string",
     *                              example="16.89"
     *                           ),
     *                          @OA\Property(
     *                              property="ImageUrl",
     *                              type="string",
     *                              example=""
     *                           ),
     *                          @OA\Property(
     *                              property="Type",
     *                              type="integer",
     *                              example="100"
     *                           ),
     *                 ),
     *             )
     *       )
     * ),
     *   @OA\Response(
     *     response="200",
     *     description="Success",
     *     @OA\JsonContent(
     *          @OA\Property(
     *               type="string",
     *               property="message",
     *          )
     *      )
     *   ),
     *   @OA\Response(
     *         response="401",
     *         description="Error",
     *     @OA\JsonContent(
     *          @OA\Property(
     *               type="string",
     *               property="message",
     *          )
     *      )
     *   ),
     *   @OA\Response(
     *         response="500",
     *         description="Error",
     *     @OA\JsonContent(
     *          @OA\Property(
     *               type="string",
     *               property="message",
     *          )
     *      )
     *   ),
     * )
     */
    public function store (CreateOrder $request, Order $order)
    {
        $this->order->store($order, $request->all());

        return $this->isSuccess();
    }

    /**
     * Display the specified resource.
     *
     * @param Order $order
     * @return \App\Http\Resources\Orders\Details
     */
    public function show (Order $order)
    {
        return $this->order->show($order);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Order $order
     * @return \App\Http\Resources\Orders\Edit|\Illuminate\Database\Eloquent\Model|Response
     */
    public function edit (Order $order)
    {
        return $this->order->edit($order);
    }

    /**
     * @OA\Patch(path="/admin/orders/{order}",
     *   tags={"Orders"},
     *   description="Update orders status",
     *   summary="Update order",
     * @OA\Parameter(
     *         description="ID of Order",
     *         in="path",
     *         name="order",
     *         required=true,
     *         @OA\Schema(
     *           type="integer",
     *           format="int64"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *      @OA\MediaType(
     *           mediaType="x-www-form-urlencoded",
     *             @OA\Schema(
     *                schema ="Order",
     *                 type="object",
     *                 @OA\Property(
     *                     property="streetaddress",
     *                     type="string",
     *                     example="Verbitskogo 12"
     *                 ),
     *                  @OA\Property(
     *                     property="type",
     *                     type="integer",
     *                     example="0 - pickup, 1 - dropoff"
     *                 ),
     *                 required={"streetaddress", "type"},
     *             )
     *       )
     * ),
     *   @OA\Response(
     *     response="200",
     *     description="Success",
     *     @OA\JsonContent(
     *          @OA\Property(
     *               type="string",
     *               property="message",
     *          )
     *      )
     *   ),
     *   @OA\Response(
     *         response="401",
     *         description="Error",
     *     @OA\JsonContent(
     *          @OA\Property(
     *               type="string",
     *               property="message",
     *          )
     *      )
     *   ),
     *  @OA\Response(
     *         response="422",
     *         description="Error",
     *     @OA\JsonContent(
     *          @OA\Property(
     *               type="string",
     *               property="message",
     *          ),
     *          @OA\Property(
     *               type="object",
     *               property="errors",
     *               @OA\Property(type="array", property="parameter", @OA\Items(type="string",description="message"))
     *          ),
     *      )
     *   ),
     *   @OA\Response(
     *         response="500",
     *         description="Error",
     *     @OA\JsonContent(
     *          @OA\Property(
     *               type="string",
     *               property="message",
     *          )
     *      )
     *   ),
     * )
     */
    public function update (UpdateOrder $request, Order $order)
    {
        $this->order->update($order, $request->only('type', 'streetaddress'));

        return $this->isSuccess();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Order $order
     * @return Response
     * @throws Exception
     */
    public function destroy (Order $order): Response
    {
        $this->order->destroy($order);

        return $this->isSuccess();
    }

    /**
     * Remove all assigned drivers from order
     *
     * @param Order $order
     * @return Response
     */
    public function detachDrivers (Order $order): Response
    {
        $this->order->detachDrivers($order);

        return $this->isSuccess();
    }

    /**
     * Get list of order statuses
     *
     * @return Response
     * @throws Exception
     */
    public function getStatuses (Order $order): Response
    {
        $this->order->getStatuses($order);

        return response(['statuses' => cache('order-statuses')]);
    }

    /**
     * @OA\Post(path="/admin/orders/{order}/cancel",
     *   tags={"Orders"},
     *   description="Make order cancelled",
     *   summary="Cancel order",
     * @OA\Parameter(
     *         description="ID of Order",
     *         in="path",
     *         name="order",
     *         required=true,
     *         @OA\Schema(
     *           type="integer",
     *           format="int64"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *      @OA\MediaType(
     *           mediaType="x-www-form-urlencoded",
     *             @OA\Schema(
     *                schema ="Order",
     *                 type="object",
     *                 @OA\Property(
     *                     property="additional_cancel_reason_id",
     *                     type="integer",
     *                     example="1"
     *                 ),
     *                  @OA\Property(
     *                     property="drivers_reason",
     *                     type="string",
     *                     example="some reason"
     *                 ),
     *                  @OA\Property(
     *                     property="admins_reason",
     *                     type="string",
     *                     example="some_reason"
     *                 ),
     *                 required={"additional_cancel_reason_id","drivers_reason","admins_reason"},
     *             )
     *       )
     * ),
     *   @OA\Response(
     *     response="200",
     *     description="Success",
     *     @OA\JsonContent(
     *          @OA\Property(
     *               type="string",
     *               property="message",
     *          )
     *      )
     *   ),
     *   @OA\Response(
     *         response="401",
     *         description="Error",
     *     @OA\JsonContent(
     *          @OA\Property(
     *               type="string",
     *               property="message",
     *          )
     *      )
     *   ),
     *  @OA\Response(
     *         response="422",
     *         description="Error",
     *     @OA\JsonContent(
     *          @OA\Property(
     *               type="string",
     *               property="message",
     *          ),
     *          @OA\Property(
     *               type="object",
     *               property="errors",
     *               @OA\Property(type="array", property="parameter", @OA\Items(type="string",description="message"))
     *          ),
     *      )
     *   ),
     *   @OA\Response(
     *         response="500",
     *         description="Error",
     *     @OA\JsonContent(
     *          @OA\Property(
     *               type="string",
     *               property="message",
     *          )
     *      )
     *   ),
     * )
     */
    public function cancel (Request $request, Order $order)
    {
        $this->order->cancel($order, $request->all());

        return $this->isSuccess();
    }

    /**
     * @OA\Patch(path="/admin/orders/{order}/set-status",
     *   tags={"Orders"},
     *   description="Set order status",
     *   summary="Set status",
     * @OA\Parameter(
     *         description="ID of Order",
     *         in="path",
     *         name="order",
     *         required=true,
     *         @OA\Schema(
     *           type="integer",
     *           format="int64"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *      @OA\MediaType(
     *           mediaType="x-www-form-urlencoded",
     *             @OA\Schema(
     *                schema ="Order",
     *                 type="object",
     *                 @OA\Property(
     *                     property="status",
     *                     type="integer",
     *                     example="1"
     *                 ),
     *                 required={"status"},
     *             )
     *       )
     * ),
     *   @OA\Response(
     *     response="200",
     *     description="Success",
     *     @OA\JsonContent(
     *          @OA\Property(
     *               type="string",
     *               property="message",
     *          )
     *      )
     *   ),
     *   @OA\Response(
     *         response="401",
     *         description="Error",
     *     @OA\JsonContent(
     *          @OA\Property(
     *               type="string",
     *               property="message",
     *          )
     *      )
     *   ),
     *  @OA\Response(
     *         response="422",
     *         description="Error",
     *     @OA\JsonContent(
     *          @OA\Property(
     *               type="string",
     *               property="message",
     *          ),
     *          @OA\Property(
     *               type="object",
     *               property="errors",
     *               @OA\Property(type="array", property="parameter", @OA\Items(type="string",description="message"))
     *          ),
     *      )
     *   ),
     *   @OA\Response(
     *         response="500",
     *         description="Error",
     *     @OA\JsonContent(
     *          @OA\Property(
     *               type="string",
     *               property="message",
     *          )
     *      )
     *   ),
     * )
     */
    public function setStatus (SetOrderStatusRequest $request, Order $order)
    {
        $this->order->setStatus($order, $request->status);

        return $this->isSuccess();
    }
}
