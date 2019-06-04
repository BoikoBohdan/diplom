<?php

namespace App\Http\Controllers;

use Illuminate\{Http\Request, Http\Response};

class AppController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param Request $request
     * @return Response
     */
    public function __invoke (Request $request)
    {
        return view('app');
    }
}
