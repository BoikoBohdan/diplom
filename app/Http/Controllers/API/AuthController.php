<?php

namespace App\Http\Controllers\API;

use App\{Http\Controllers\Controller, Http\Requests\API\Auth\Login};
use Illuminate\Http\JsonResponse;
use JWTAuth;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct ()
    {
        $this->middleware('auth:api', ['except' => ['login']]);
    }

    /**
     * @OA\Post(path="/login",
     *   tags={"Auth"},
     *   summary="Login user into the system",
     *   operationId="loginUser",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *           mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="email",
     *                     type="email",
     *                     example="driver@driver.com"
     *                 ),
     *                 @OA\Property(
     *                     property="password",
     *                     type="string"
     *                 ),
     *                 required={"email","password"}
     *             )
     *         )
     *     ),
     *   @OA\Response(
     *     response="200",
     *     description="Auth User Token",
     *     @OA\JsonContent(
     *          @OA\Property(
     *               type="string",
     *               property="token",
     *               description=""
     *          ),
     *          @OA\Property(
     *               type="string",
     *               property="role",
     *               description="Role of authenticated user"
     *          ),
     *          @OA\Property(
     *               type="integer",
     *               property="id",
     *               description="ID of authenticated user"
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
    public function login (Login $request)
    {
        $credentials = $request->all();

        if (!$token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $user = auth()->user();

        if ($user->status === $user::INACTIVE_STATUS) {
            throw new \RuntimeException('User is blocked', 401);
        }

        return response()->json([
            'token' => $token,
            'role' => $user->getRole(),
            'id' => $user->id,
            'company_id' => $user->company_id,
            'group_guid' => $user->company->guid ?? null
        ]);
    }

    /**
     * @OA\Get(path="/logout",
     *   tags={"Auth"},
     *   summary="Logout user into the system",
     *   operationId="logoutUser",
     *   @OA\Response(
     *     response="200",
     *     description="Success",
     *     @OA\JsonContent(
     *          @OA\Property(
     *               type="string",
     *               property="message",
     *               example="success"
     *          )
     *      )
     *   ),
     *     @OA\Response(
     *         response="500",
     *         description="Error",
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
     *   security={{"bearerAuth":{}}}
     * )
     */
    public function logout ()
    {
        auth()->logout();

        return $this->isSuccess();
    }

    /**
     * Refresh a token.
     *
     * @return JsonResponse
     */
    public function refresh ()
    {
        return $this->respondWithToken(auth()->refresh());
    }
}
