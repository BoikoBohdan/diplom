<?php

namespace App\Http\Controllers\API;

use App\{Http\Controllers\Controller, Mail\NewPasswordMail, User};
use Illuminate\{Http\Request, Support\Facades\Mail, Support\Str};

class ApiForgotPasswordController extends Controller
{
    /**
     * @OA\Post(path="/password-forgot",
     *   tags={"Auth"},
     *   summary="Send new password when user forgot his password",
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
     *                 required={"email"}
     *             )
     *         )
     *     ),
     *   @OA\Response(
     *     response="200",
     *     description="Success",
     *     @OA\JsonContent(
     *          @OA\Property(
     *               type="string",
     *               property="message",
     *          ),
     *      )
     *   ),
     *   @OA\Response(
     *         response="401",
     *         description="Error",
     *     @OA\JsonContent(
     *          @OA\Property(
     *               type="string",
     *               property="error",
     *          )
     *      )
     *   ),
     *   @OA\Response(
     *         response="422",
     *         description="Error",
     *     @OA\JsonContent(
     *          @OA\Property(
     *               type="string",
     *               property="error",
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
     *               property="error",
     *          )
     *      )
     *   ),
     * )
     */
    public function sendNewPasswordEmail (Request $request)
    {
        $user = User::where('email', $request->email)->firstOrFail();

        try {
            $newPassword = Str::random(10);

            $user->password = bcrypt($newPassword);

            Mail::to($user)->send(new NewPasswordMail($newPassword, $user->getFullNameAttribute()));

            $user->save();

            return response()->json(['message' => 'Email with new password was sent.']);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }
}
