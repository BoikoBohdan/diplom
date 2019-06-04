<?php

namespace App\Http\Controllers\API\Driver;

use App\{Http\Controllers\Controller,
    Http\Requests\API\Mobile\ChangeDriverCoordinatesRequest,
    Services\Mobile\DriverMobileService,
    UsersWorkLocations};
use Tymon\JWTAuth\Facades\JWTAuth;

class DriverController extends Controller
{
    protected $service;

    public function __construct (DriverMobileService $service)
    {
        $this->service = $service;
    }

    /**
     * @OA\Get(path="/driver/details",
     *   tags={"Mobile App"},
     *   description="Get driver detailes",
     *   summary="Get driver detailes",
     * @OA\Parameter (
     *         required=true,
     *         parameter="driver",
     *         name="driver ID",
     *         in="path",
     *         @OA\Schema (
     *             type="integer"
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
    public function show ()
    {
        $user = JWTAuth::parseToken()->authenticate();

        return $this->service->show($user);
    }

    /**
     * @OA\Post(path="/driver/set-coordinates",
     *   tags={"Mobile App"},
     *   description="Create or update driver coordinates",
     *   summary="Set driver coordinates",
     *     @OA\RequestBody(
     *         required=true,
     *      @OA\MediaType(
     *           mediaType="x-www-form-urlencoded",
     *             @OA\Schema(
     *                schema ="Driver",
     *                 type="object",
     *                 @OA\Property(
     *                     property="lat",
     *                     type="string",
     *                     example="some lattitude"
     *                 ),
     *                  @OA\Property(
     *                     property="lng",
     *                     type="string",
     *                     example="some longtitude"
     *                 ),
     *                 required={"lat", "lng"},
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
    public function setCoordinates (ChangeDriverCoordinatesRequest $request)
    {
        $user = JWTAuth::parseToken()->authenticate();

        $this->service->setCoordinates($user->driver, $request->only(['lat', 'lng']));

        return $this->isSuccess();
    }

    /**
     * @OA\Patch(path="driver/worklocations/{location}/set-active",
     *   tags={"Mobile App"},
     *   description="Set driver work location",
     *   summary="Set driver work location",
     * @OA\Parameter(
     *         description="ID of Location",
     *         in="path",
     *         name="location",
     *         required=true,
     *         @OA\Schema(
     *           type="integer",
     *           format="int64"
     *         )
     *     ),
     *
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

    public function setActiveWorkLocation (UsersWorkLocations $location)
    {
        $this->service->setActiveWorkLocation($location);

        return $this->isSuccess();
    }

    /**
     * @OA\Patch(path="driver/set-is-shift-status",
     *   tags={"Mobile App"},
     *   description="Set driver is_shift status",
     *   summary="Set driver is_shift status",
     *
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
    public function setShiftStatus ()
    {
        $this->service->setIsShiftStatus();

        return $this->isSuccess();
    }
}
