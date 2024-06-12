<?php

namespace App\Exceptions;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $e)
    {
        if ($e instanceof AuthorizationException) {
            return response()->json([
                'message' => 'This action is unauthorized.',
            ], 403);
        }

        if ($e instanceof ModelNotFoundException) {
            return response()->json([
                'data'      => null,
                'message'   => 'No Data Found.',
                'status'    => 404
            ], 404);
        }

        if ($e instanceof NotFoundHttpException) {
            return response()->json([
                'data'      => null,
                'message'   => 'Route Not Found.',
                'status'    => 404
            ], 404);
        }

        if ($e instanceof MethodNotAllowedHttpException) {
            return response()->json([
                'data'      => null,
                'message'   => 'Method Not Allowed for this Route.',
                'status'    => 404
            ], 404);
        }

        return parent::render($request, $e);
    }
}
