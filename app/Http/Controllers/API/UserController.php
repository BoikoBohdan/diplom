<?php

namespace App\Http\Controllers\API;

use App\{Http\Controllers\Controller, User};
use Carbon\Carbon;
use Illuminate\{Http\Request, Http\Response, Validation\ValidationException};

class UserController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param Request $request
     * @return Response
     * @throws ValidationException
     */
    public function __invoke (Request $request)
    {
        $this->validate($request, [
            'token' => 'required|string|exists:users,invite_token',
            'password' => 'required|string|min:6|max:100|confirmed',
            'password_confirmation' => 'required|string|min:6|max:100',
            'phone' => 'required|string|regex:/^\+[0-9]{12}$/'
        ]);

        $request->merge([
            'password' => bcrypt($request->password),
            'status' => true,
            'invite_token_verified_at' => Carbon::now()
        ]);

        $user = User::where('invite_token', $request->token)->first();
        $user->update($request->except(['token', 'password_confirmation']));

        return response()->json(['message' => 'success']);
    }
}
