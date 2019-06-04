<?php /** @noinspection PropertyInitializationFlawsInspection */

namespace App\Exceptions;

use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;


class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        /** @lang text */
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param Exception $exception
     * @return void
     * @throws Exception
     */
    public function report (Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param Request $request
     * @param Exception $exception
     * @return \Illuminate\Http\JsonResponse|Response|\Symfony\Component\HttpFoundation\Response
     */
    public function render ($request, Exception $exception)
    {
        if ($exception instanceof ModelNotFoundException) {
            return response()->json(['error' => 'Model not found.'], 500);
        }

        if ($exception instanceof UnauthorizedHttpException) {
            return response()->json([$exception->getMessage()], $exception->getStatusCode());
        }

        if ($exception instanceof TokenExpiredException) {
            return response()->json(['error' => 'Token has expired'], 401);
        }

        if ($exception instanceof TokenInvalidException) {
            return response()->json(['error' => 'Token is invalid'], 401);
        }

        if ($exception instanceof JWTException) {
            return response()->json(['error' => 'Auth attempt error'], 401);
        }
        if ($exception instanceof \RuntimeException) {
            return response()->json(['error' => $exception->getMessage()], $exception->getCode());
        }

        if ($exception instanceof AuthorizationException) {
            return response()->json(['error' => 'Action not authorized'], 403);
        }

        if ($this->isHttpException($exception)) {
            switch ($exception->getStatusCode()) {
                case 401:
                    return response()->json(['error' => 'Unauthorized'], 401);
                    break;
                case 403:
                    return response()->json(['error' => 'Forbidden'], 403);
                    break;
                case 404:
                    return response()->json(['error' => 'Page not found'], 404);
                    break;
                case 405:
                    return response()->json(['error' => 'Method not allowed'], 405);
                    break;
                case 500:
                    return response()->json(['error' => 'Server error'], 500);
                    break;

                default:
                    return $this->renderHttpException($exception);
                    break;
            }
        } else {
            return parent::render($request, $exception);
        }
    }
}
