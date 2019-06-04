<?php

namespace App\Http\Controllers\API\Admin;

use App\{Http\Controllers\Controller,
    Http\Requests\API\Shifts\CreateShiftRequest,
    Http\Requests\API\Shifts\UpdateShiftRequest,
    Http\Resources\Shifts\EditShiftResource,
    Http\Resources\Shifts\ShiftCollection,
    Services\ShiftService,
    Shift};
use Illuminate\Http\Request;

class ShiftsController extends Controller
{
    public $service;

    /**
     * ShiftsController constructor.
     */
    public function __construct (ShiftService $service)
    {
        $this->service = $service;

        $this->authorizeResource(Shift::class, 'shift');
    }

    /**
     * Display a listing of the resource.
     *
     * @return ShiftCollection
     */
    public function index (Request $request, Shift $shifts)
    {
        $result = $this->service->allShifts($request, $shifts);

        return response($result)->withHeaders([
            'X-Total-Count' => $result->total(),
            'Access-Control-Expose-Headers' => 'X-Total-Count'
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function create ()
    {
        $result = $this->service->getInfoForCreate();

        return response()->json($result);
    }

    /**
     * @OA\Post(path="/admin/shifts",
     *   tags={"Shifts"},
     *   description="Add new shift",
     *   summary="Create new shift",
     *     @OA\RequestBody(
     *         required=true,
     *      @OA\MediaType(
     *           mediaType="application/json",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(
     *                     property="date",
     *                     type="string",
     *                     example="2019-04-25"
     *                 ),
     *                  @OA\Property(
     *                     property="city_id",
     *                     type="integer",
     *                     example="1"
     *                 ),
     *                  @OA\Property(
     *                     property="start",
     *                     type="string",
     *                     example="19:20"
     *                 ),
     *                  @OA\Property(
     *                     property="end",
     *                     type="string",
     *                     example="20:15"
     *                 ),
     *                  @OA\Property(
     *                     property="meal",
     *                     type="integer",
     *                     example="1"
     *                 ),
     *             )
     *      )
     *    ),
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

    /**
     * Store a newly created resource in storage.
     *
     * @param CreateShiftRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store (CreateShiftRequest $request, Shift $model)
    {
        $this->service->store($model, $request->all());

        return $this->isSuccess();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Shift $shift
     * @return EditShiftResource
     */
    public function edit (Shift $shift)
    {
        return new EditShiftResource($shift);
    }

    /**
     * @OA\Patch(path="/admin/shifts/{shift}",
     *   tags={"Shifts"},
     *   description="Update shift",
     *   summary="Update shift",
     *     @OA\RequestBody(
     *         required=true,
     *      @OA\MediaType(
     *           mediaType="application/json",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(
     *                     property="date",
     *                     type="string",
     *                     example="2019-04-25"
     *                 ),
     *                  @OA\Property(
     *                     property="city_id",
     *                     type="integer",
     *                     example="1"
     *                 ),
     *                  @OA\Property(
     *                     property="start",
     *                     type="string",
     *                     example="19:20"
     *                 ),
     *                  @OA\Property(
     *                     property="end",
     *                     type="string",
     *                     example="20:15"
     *                 ),
     *                  @OA\Property(
     *                     property="meal",
     *                     type="integer",
     *                     example="1"
     *                 ),
     *             )
     *      )
     *    ),
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

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateShiftRequest $request
     * @param Shift $shift
     * @return \Illuminate\Http\Response
     */
    public function update (UpdateShiftRequest $request, Shift $shift)
    {
        $this->service->update($shift, $request->all());

        return $this->isSuccess();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Shift $shift
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy (Shift $shift)
    {
        $shift->drivers()->detach();
        $this->service->destroy($shift);

        return $this->isSuccess();
    }
}
