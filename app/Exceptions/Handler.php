<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use App\Exceptions\OutOfStockException;
use App\Exceptions\BookNotFoundException;
use Illuminate\Auth\Access\AuthorizationException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
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
     */
    public function register(): void
    {
        $this->renderable(function (OutOfStockException $exception) {
            return response()->json([

                'success' => false,
                'message' => $exception->getMessage(),

            ], $exception->getCode());
        });

        $this->renderable(function (BookNotFoundException $exception) {
            return response()->json([

                'success' => false,
                'message' => $exception->getMessage(),

            ], $exception->getCode());
        });
     
        $this->renderable(function (AccessDeniedHttpException  $exception) {
            return response()->json([

                'success' => false,
                'message' => $exception->getMessage(),

            ], 403);
        });
    }
}
