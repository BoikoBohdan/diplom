<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\{Http\Request, Http\Response};
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class InviteController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param Request $request
     * @param string $token
     * @return Response
     * @throws NotFoundHttpException
     */
    public function __invoke (Request $request, $token)
    {
        $user = User::where('invite_token', $token)->first();

        if (empty($user)) {
            throw new NotFoundHttpException('Sorry, the token not found.');
        }

        $expired = 0;

        if ($user->invite_token_verified_at !== null) {
            $expired = 1;
        }

        return redirect(route('index', [
                'token' => $user->invite_token,
                'expired' => $expired
            ]) . '#/new_user');
    }
}
