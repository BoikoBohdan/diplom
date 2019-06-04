<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;


/**
 * @OA\OpenApi(
 *     @OA\Info(
 *         version="1.0.0",
 *         title="Eat.ch",
 *         description="",
 *     ),
 *     @OA\Server(
 *         description="OpenApi host",
 *         url="/api"
 *     ),
 *     @OA\PathItem(
 *         path="api/documentation"
 *     )
 * )
 */

/**
 * @OA\SecurityScheme(
 *      securityScheme="bearerAuth",
 *      type="http",
 *      scheme="bearer"
 * )
 **/
class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected function isSuccess ()
    {
        return response()->json(['message' => 'success']);
    }

    protected function isSuccessWithData ($attributes)
    {
        return response()->json([
            'message' => 'success',
            'data' => $attributes
        ]);
    }

    protected function isError (Exception $e)
    {
        return response()->json([
            'message' => $e->getMessage()
        ], $e->getCode());
    }
}
