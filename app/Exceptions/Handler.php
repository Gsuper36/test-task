<?php

namespace App\Exceptions;

use App\Helpers\ApiResponseHelper;
use Exception;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

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
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $e)
    {
        if ($request->ajax() ||
            $request->wantsJson()
        ) {
            return $this->apiError($request, $e);
        }

        if ($e instanceof Exception) {
            response($e->getMessage(), 500);
        }

        dd($e);
    }

    private function apiError($request, Exception $e)
    {

        if ($e instanceof ValidationException) {
            return ApiResponseHelper::error(
                $e->errors()
            );
        }

        return ApiResponseHelper::error(
            ['message' => $e->getMessage()]
        );
    }
}
