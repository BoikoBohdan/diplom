<?php

namespace App\Http\Controllers\API\Driver;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Shift;
use App\Services\Mobile\ShiftMobileService;

class ShiftsController extends Controller
{
    public $service;

    public function __construct(ShiftMobileService $service)
    {
        $this->service = $service;
    }

    /**
     * @OA\Get (
     *     path="/driver/shifts",
     *     tags={"Mobile App"},
     *     summary="Get shifts list",
     *     operationId="shiftsList",
     *     @OA\Response (
     *         response="200",
     *         description="Shifts list",
     *         @OA\JsonContent (
     *             @OA\Items (
     *                 @OA\Property (
     *                     property="2019-05-16",
     *                     type="array",
     *                     @OA\Items (
     *                           @OA\Property (
     *                              property="id",
     *                              type="integer"
     *                           ),
     *                           @OA\Property (
     *                              property="date",
     *                              type="string"
     *                           ),
     *                           @OA\Property (
     *                              property="city",
     *                              type="string"
     *                           ),
     *                           @OA\Property (
     *                              property="start",
     *                              type="string"
     *                           ),
     *                           @OA\Property (
     *                              property="end",
     *                              type="string"
     *                           ),
     *                           @OA\Property (
     *                              property="meal",
     *                              type="string"
     *                           ),
     *                           @OA\Property (
     *                              property="assigned",
     *                              type="boolean"
     *                           ),
     *                      ),
     *                  ),
     *              ),
     *            )
     *      ),
     * @OA\Response (
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
    /**
     * Display a listing of the resource.
     *
     * @return ShiftsForDriverCollection
     */
    public function getShifts(Request $request, Shift $shifts)
    {
        return $this->service->getShiftsForDriver($request, $shifts);
    }

    /**
     * @OA\Patch (
     *     path="/driver/shifts/{shift}",
     *     tags={"Mobile App"},
     *     summary="Set/unset driver on shift",
     *     @OA\Parameter (
     *         required=true,
     *         parameter="shift",
     *         name="shift",
     *         in="path",
     *         @OA\Schema (
     *             type="integer"
     *         )
     *     ),
     *      @OA\RequestBody(
     *         required=true,
     *          @OA\MediaType(
     *               mediaType="application/json",
     *              )
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
     *         ),
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
     *         description="Shift not found error",
     *         @OA\JsonContent (
     *             @OA\Property (
     *                 property="message",
     *                 type="string",
     *                 example="Model not found"
     *             )
     *         )
     *     ),
     *  ),
     *  security={{"bearerAuth":{}}}
     * )
     */

     /**
     * Set or unset driver on shifts.
     *
     * @param Shift $shift
     * @return \Illuminate\Http\Response
     */
    public function setDriverOnShifts(Shift $shift)
    {
        auth()->user()->driver->shifts()->toggle($shift->id);

        return $this->isSuccess();
    }
}
