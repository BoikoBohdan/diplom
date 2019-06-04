<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use Illuminate\Http\Request;
use JWTAuth;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle ($request, Closure $next, $roles)
    {
        $roles = explode('|', $roles);
        try {
            $user = JWTAuth::parseToken()->authenticate();
        } catch (Exception $e) {
            return response()->json(['message' => 'user_not_authenticated'], 401);
        }
        if (!$user->hasRole($roles)) {
            return response()->json(['message' => 'permission_denied'], 401);
        }

        return $next($request);
    }
}
