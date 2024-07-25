<?php

namespace App\Exceptions;

use Throwable;
use Exception;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;
use Auth;

use App\WevelopeLibs\JsonResponse;
use App\Libs\ErrorCode;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        // $this->reportable(function (Throwable $e) {
            //
        // });

        $this->renderable(function (Exception $exception, $request) {
            if($request->is('api/*')) {
                if($exception instanceof Exception) {
                    $jsonResponse = new JsonResponse();
                    $jsonResponse->setError(true);
                    if($exception instanceof ValidationException) {
                        $jsonResponse->setValidationException($exception);
                    } elseif ($exception instanceof AuthenticationException) {
                        $jsonResponse->setMessage('Invalid API Token!');
                        $jsonResponse->setErrorCode(ErrorCode::INVALID_TOKEN);
                    // } elseif ($exception instanceof \PDOException) {
                        // $jsonResponse->setMessage('Data sedang digunakan. Tidak dapat dihapus.');
                        // $jsonResponse->setErrorCode(500);
                    } else {
                        $jsonResponse->setMessage($exception->getMessage());
                    }

                    if(env('APP_ENV') == 'local')
                        $jsonResponse->setTraces($exception->getTrace());

                    // Cannot use regular array
                    // return $jsonResponse->getResponse();
                    return response()->json($jsonResponse->getResponse());
                }
            }

            // var_dump($request->routeIs('member.auth.login.store')); die;

            if ($request->routeIs('member.auth.login.store')) {
                // return redirect()->route('homepage');

                return back()
                    ->with('error', $exception->getMessage());
            } else {
                // return redirect()->route('landingpage.homepage');
                // var_dump($exception->getTrace());
                // die('test');

                /* BUG, saat terjadi error di viewnya.
                 * Sehingga membuat "back" ke halaman sebelumnya
                 * Ini menjadi masalah, saat "back" ternyata error juga,
                 * Sehingga terjadi loop
                return back()
                    ->with('error', $exception->getMessage())
                    ->withInput();
                 * 
                 */
            }
        });
    }
}
