<?php

namespace App\Http\Controllers\API\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\City;
use App\Services\CityService;
use App\Http\Requests\API\Cities\CreateCityRequest;

class CitiesController extends Controller
{

    public $service;

    /**
     * ShiftsController constructor.
     */
    public function __construct (CityService $service)
    {
        $this->service = $service;
    }

    /**
     * Display a listing of the resource.
     *
     * @return CityCollection
     */
    public function index(Request $request, City $cities)
    {
        $result = $this->service->allCities($request, $cities);

        return response($result)->withHeaders([
            'X-Total-Count' => $result->total(),
            'Access-Control-Expose-Headers' => 'X-Total-Count'
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * @OA\Post(path="/admin/cities",
     *   tags={"Cities"},
     *   description="Add new city",
     *   summary="Create new city",
     *     @OA\RequestBody(
     *         required=true,
     *      @OA\MediaType(
     *           mediaType="application/json",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(
     *                     property="name",
     *                     type="string",
     *                     example="Zurich"
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
     * @param  App\Http\Requests\API\Cities\CreateCityRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateCityRequest $request, City $model)
    {
        $this->service->store($model, $request->all());

        return $this->isSuccess();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  City $city
     * @return \Illuminate\Http\Response
     */
    public function destroy(City $city)
    {
        $this->service->destroy($city);

        return $this->isSuccess();
    }
}
