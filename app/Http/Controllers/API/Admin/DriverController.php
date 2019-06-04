<?php

namespace App\Http\Controllers\API\Admin;

use App\{Driver,
    Http\Controllers\Controller,
    Http\Requests\API\BulkActions\BulkDestroy,
    Http\Requests\API\BulkActions\BulkUpdate,
    Http\Requests\API\Driver\CreateDriver,
    Http\Requests\API\Driver\UpdateDriver,
    Services\DriverService,
    User};
use Illuminate\Http\Request;

class DriverController extends Controller
{
    private $service;

    public function __construct (DriverService $service)
    {
        $this->service = $service;

        $this->authorizeResource(User::class, 'driver');
    }

    /**
     * @OA\Get(path="/admin/drivers",
     *   tags={"Drivers"},
     *   summary="Index all drivers",
     *   description="Get all driver from database",
     *   @OA\Response(
     *     response="200",
     *     description="Success",
     *     @OA\JsonContent(
     *          @OA\Property(
     *               type="object",
     *               property="drivers",
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
    public function index (Request $request, Driver $drivers)
    {
        $result = $this->service->all($request, $drivers);

        return response($result)->withHeaders([
            'X-Total-Count' => $result->total(),
            'Access-Control-Expose-Headers' => 'X-Total-Count'
        ]);
    }

    /**
     * @OA\Post(path="/admin/drivers",
     *   tags={"Drivers"},
     *   description="Add new driver to database, in future this method will send notification to driver",
     *   summary="Create new driver",
     *     @OA\RequestBody(
     *         required=true,
     *      @OA\MediaType(
     *           mediaType="x-www-form-urlencoded",
     *             @OA\Schema(
     *                schema ="Driver",
     *                 type="object",
     *                 @OA\Property(
     *                     property="first_name",
     *                     type="string",
     *                     example="Developer"
     *                 ),
     *                  @OA\Property(
     *                     property="last_name",
     *                     type="string",
     *                     example="Vlad"
     *                 ),
     *                  @OA\Property(
     *                     property="email",
     *                     type="email",
     *                     example="test@email.com"
     *                 ),
     *                  @OA\Property(
     *                     property="phone",
     *                     type="string",
     *                     example="+411234567890"
     *                 ),
     *                  @OA\Property(
     *                     property="address",
     *                     type="string",
     *                     example="Ukraine, Kiev, etc"
     *                 ),
     *                  @OA\Property(
     *                     property="company_id",
     *                     type="integer",
     *                     example="1"
     *                 ),
     *                  @OA\Property(
     *                     property="image",
     *                     type="string",
     *                     example="here we put a base:64 encoded image, this field is not required"
     *                 ),
     *                  @OA\Property(
     *                     property="status",
     *                     type="boolean",
     *                     example="true or false, 0 or 1"
     *                 ),
     *
     *                 required={"first_name","last_name","email","phone","address","company_id","status"},
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
    public function store (CreateDriver $request, User $newUser)
    {
        $this->service->store($newUser, $request->all());

        return $this->isSuccess();
    }

    /**
     * @OA\Get(path="/admin/drivers/{id}/edit",
     *   tags={"Drivers"},
     *   summary="Get driver for edit",
     *   description="Get selected driver and send him for update, a path shuld look like (api/admin/drivers/1/edit)",
     *   @OA\Parameter(
     *         description="ID of user to return",
     *         in="path",
     *         name="user_id",
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
    public function edit (User $driver)
    {
        return $this->service->edit($driver);
    }

    /**
     * @OA\Put(path="/admin/drivers/{id}",
     *   tags={"Drivers"},
     *   description="Update selected driver",
     *   summary="Update selected driver",
     *     @OA\RequestBody(
     *         required=true,
     *      @OA\MediaType(
     *           mediaType="x-www-form-urlencoded",
     *             @OA\Schema(
     *                schema ="Driver",
     *                 type="object",
     *                 @OA\Property(
     *                     property="first_name",
     *                     type="string",
     *                     example="Developer"
     *                 ),
     *                  @OA\Property(
     *                     property="last_name",
     *                     type="string",
     *                     example="Vlad"
     *                 ),
     *                  @OA\Property(
     *                     property="email",
     *                     type="email",
     *                     example="test@email.com"
     *                 ),
     *                  @OA\Property(
     *                     property="phone",
     *                     type="string",
     *                     example="+411234567890"
     *                 ),
     *                  @OA\Property(
     *                     property="address",
     *                     type="string",
     *                     example="Ukraine, Kiev, etc"
     *                 ),
     *                  @OA\Property(
     *                     property="company_id",
     *                     type="integer",
     *                     example="1"
     *                 ),
     *                  @OA\Property(
     *                     property="image",
     *                     type="string",
     *                     example="here we put a base:64 encoded image, this field is not required"
     *                 ),
     *                  @OA\Property(
     *                     property="status",
     *                     type="boolean",
     *                     example="true or false, 0 or 1"
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
    public function update (UpdateDriver $request, User $driver)
    {
        $this->service->update($driver, $request->all());

        return $this->isSuccess();
    }

    /**
     * @OA\Delete(path="/admin/drivers/{id}",
     *   tags={"Drivers"},
     *   summary="Delete selected driver",
     *   description="Get selected driver and delete him with all his relations (vehicle, work locations)",
     *   @OA\Parameter(
     *         description="ID of user to delete",
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
    public function destroy (User $driver)
    {
        $this->service->destroy($driver);

        return $this->isSuccess();
    }

    /**
     * @OA\Delete(path="/admin/drivers/bulk-delete",
     *   tags={"Drivers"},
     *   summary="Delete group of drivers",
     *   description="Choose drivers and delete all of them",
     *  @OA\RequestBody(
     *         required=true,
     *      @OA\MediaType(
     *           mediaType="x-www-form-urlencoded",
     *             @OA\Schema(
     *                schema ="Driver",
     *                 type="array",
     *                 @OA\Items(
     *                     type="integer"
     *                 ),
     *                  example="data[1,2,3]",
     *
     *                 required={"data"},
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
    public function bulkDestroy (BulkDestroy $request, User $users)
    {
        $this->service->bulkDestroy($users, $request);

        return $this->isSuccess();
    }

    /**
     * @OA\Put(path="/admin/drivers/bulk-update",
     *   tags={"Drivers"},
     *   summary="Update status for group of drivers",
     *   description="Choose drivers and update status for them",
     *  @OA\RequestBody(
     *         required=true,
     *      @OA\MediaType(
     *           mediaType="x-www-form-urlencoded",
     *             @OA\Schema(
     *                schema ="Driver",
     *                 type="array",
     *                 @OA\Items(
     *                     type="integer"
     *                 ),
     *                     @OA\Items(
     *                     type="integer"
     *                 ),
     *                  example="data[1,2,3], status[1]",
     *
     *                 required={"data", "status"},
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
    public function bulkUpdate (BulkUpdate $request, User $users)
    {
        $this->service->bulkUpdate($users, $request->all());

        return $this->isSuccess();
    }
}
