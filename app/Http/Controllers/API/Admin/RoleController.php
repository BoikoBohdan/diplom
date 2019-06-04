<?php

namespace App\Http\Controllers\API\Admin;

use App\{Http\Controllers\Controller, Role};
use Illuminate\Http\Response;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index ()
    {
        $user = JWTAuth::parseToken()->authenticate();
        switch ($user->getRole()) {
            case 'master_admin':
                $roles_array = ['master_admin', 'super_admin', 'admin'];
                break;
            case 'super_admin':
                $roles_array = ['super_admin', 'admin'];
                break;
            case 'admin':
                $roles_array = ['admin'];
                break;
            default:
                $roles_array = [];
                break;
        }

        return response()->json(Role::whereIn('name', $roles_array)->get());
    }
}
