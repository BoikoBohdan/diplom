<?php

namespace App\Http\Controllers\API\Driver;

use App\{Http\Controllers\Controller, Http\Requests\API\Mobile\CreateRatingRequest};
use Tymon\JWTAuth\Facades\JWTAuth;

class ScoreController extends Controller
{
    /**
     * @OA\Post(path="/driver/set-score",
     *   tags={"Mobile App"},
     *   description="Add rating for restaurant or customer",
     *   summary="Create new rating",
     *     @OA\RequestBody(
     *         required=true,
     *      @OA\MediaType(
     *           mediaType="x-www-form-urlencoded",
     *             @OA\Schema(
     *                schema ="Rating",
     *                 type="object",
     *                 @OA\Property(
     *                     property="message",
     *                     type="string",
     *                     example="Some text"
     *                 ),
     *                  @OA\Property(
     *                     property="rating",
     *                     type="integer",
     *                     example="2"
     *                 ),
     *                  @OA\Property(
     *                     property="ratingable_type",
     *                     type="string",
     *                     example="restaurant",
     *                      example="customer"
     *                 ),
     *                  @OA\Property(
     *                     property="ratingable_id",
     *                     type="integer",
     *                     example="4"
     *                 ),
     *                 required={"message","rating","ratingable_type","ratingable_id"},
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
    public function setScore (CreateRatingRequest $request)
    {
        $user = JWTAuth::parseToken()->authenticate();

        $user->driver->ratings()->create($request->all());

        return $this->isSuccess();
    }
}
