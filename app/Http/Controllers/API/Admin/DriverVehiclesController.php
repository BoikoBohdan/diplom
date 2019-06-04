<?php

namespace App\Http\Controllers\API\Admin;

use App\{Http\Controllers\Controller,
    Http\Requests\API\Driver\CreateDriverVehicle,
    Http\Requests\API\Driver\UpdateDriverVehicle,
    Http\Resources\Vehicles\Collection,
    Services\DriverVehicleService,
    Vehicle};
use Illuminate\Http\Request;

class DriverVehiclesController extends Controller
{
    private $service;

    public function __construct (DriverVehicleService $service)
    {
        $this->service = $service;
    }

    /**
     * @OA\Get(path="/admin/vehicles",
     *   tags={"Driver Vehicles"},
     *   summary="Index all vehicles",
     *   description="Get all vehicles from database",
     *   @OA\Response(
     *     response="200",
     *     description="Success",
     *     @OA\JsonContent(
     *          @OA\Property(
     *               type="object",
     *               property="vehicles",
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
    public function index (Request $request, Vehicle $vehicle)
    {
        $resource = $this->service->all($request, $vehicle);

        return response($resource)
            ->withHeaders([
                'X-Total-Count' => $resource->total(),
                'Access-Control-Expose-Headers' => 'X-Total-Count'
            ]);
    }

    /**
     * @OA\Post(path="/admin/vehicles",
     *   tags={"Driver Vehicles"},
     *   description="Add new vehicle assigned for driver in database",
     *   summary="Create new vehicle",
     *     @OA\RequestBody(
     *         required=true,
     *      @OA\MediaType(
     *           mediaType="x-www-form-urlencoded",
     *             @OA\Schema(
     *                schema ="Vehicles",
     *                 type="object",
     *                 @OA\Property(
     *                     property="name",
     *                     type="string",
     *                     example="Toyota"
     *                 ),
     *                  @OA\Property(
     *                     property="driver_id",
     *                     type="integer",
     *                     example="1",
     *                 ),
     *                  @OA\Property(
     *                     property="vehicle_type_id",
     *                     type="integer",
     *                     example="1"
     *                 ),
     *                  @OA\Property(
     *                     property="is_shift",
     *                     type="integer",
     *                     example="0"
     *                 ),
     *                 required={"name","driver_id","vehicle_type_id","is_shift"},
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
    public function store (CreateDriverVehicle $request, Vehicle $vehicle)
    {
        $this->service->store($vehicle, $request->all());

        return $this->isSuccessWithData(
            new Collection(
                Vehicle::where('driver_id', $request->driver_id)->get()
            )
        );
    }

    /**
     * @OA\Get(path="/admin/vehicles/{id}/edit",
     *   tags={"Driver Vehicles"},
     *   summary="Get vehicle for edit",
     *   description="Get selected vehicle and send it for update, a path shuld look like (api/admin/vehicles/1/edit)",
     *   @OA\Parameter(
     *         description="ID of vehicle to return",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(
     *           type="integer",
     *           format="int64"
     *         )
     *     ),
     *   @OA\Response(
     *     response="200",
     *     description="Success",
     *     @OA\JsonContent(
     *         @OA\Schema(
     *            schema ="Driver",
     *            type="object",
     *              @OA\Property(
     *              type="object",
     *              property="driver",
     *            )
     *         )
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
    public function edit (Vehicle $vehicle)
    {
        return $this->service->edit($vehicle);
    }

    /**
     * @OA\Put(path="/admin/vehicles/{id}",
     *   tags={"Driver Vehicles"},
     *   description="Update selected vehicle",
     *   summary="Update selected vehicle",
     * @OA\Parameter(
     *         description="ID of vehicle to update",
     *         in="path",
     *         name="id",
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
     *                     property="name",
     *                     type="string",
     *                     example="Toyota"
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
    public function update (UpdateDriverVehicle $request, Vehicle $vehicle)
    {
        $this->service->update($vehicle, $request->all());

        return $this->isSuccessWithData(new Collection(
                Vehicle::where('driver_id', $request->driver_id)->get())
        );
    }

    public function destroy (Vehicle $vehicle)
    {
        $this->service->destroy($vehicle);

        return $this->isSuccess();
    }
}
