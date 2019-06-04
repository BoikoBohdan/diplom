<?php

namespace App\Http\Controllers\API\Driver;

use App\{Http\Controllers\Controller,
    Http\Requests\API\Driver\SetVehicleStatusRequest,
    Services\DriverVehicleService,
    Vehicle};

class VehicleController extends Controller
{
    public $service;

    /**
     * Constructor
     *
     * @param DriverVehicleService $service
     */
    public function __construct (DriverVehicleService $service)
    {
        $this->service = $service;
    }

    /**
     * @OA\Patch(path="driver/vehicles/{vehicle}/set-status",
     *   tags={"Mobile App"},
     *   description="Set driver status",
     *   summary="Set driver status",
     * @OA\Parameter(
     *         description="ID of Vehicle",
     *         in="path",
     *         name="vehicle",
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
     *                schema ="Vehicle",
     *                 type="object",
     *                 @OA\Property(
     *                     property="is_shift",
     *                     type="boolean",
     *                     example="true / false"
     *                 ),
     *                 required={"is_shift"},
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
     * security={{"bearerAuth":{}}}
     * )
     */
    public function setStatus (SetVehicleStatusRequest $request, Vehicle $vehicle)
    {
        $this->service->setStatus($vehicle, $request->only('is_shift'));

        return $this->isSuccess();
    }
}
