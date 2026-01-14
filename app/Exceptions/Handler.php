<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    protected function unauthenticated($request, AuthenticationException $exception)
    {
       return response()->json([
            'status'  => false,
            'message' => 'Unauthenticated',
        ], 401);
    }

    public function render($request, Throwable $e)
    {
        // Spatie role / permission denied
        if ($e instanceof UnauthorizedException) {
            return response()->json([
                'status'  => false,
                'message' => 'Access denied. Insufficient permissions.',
            ], 403);
        }

        // Laravel policy / gate denied
        if ($e instanceof AuthorizationException) {
            return response()->json([
                'status'  => false,
                'message' => 'Access denied.',
            ], 403);
        }

        return parent::render($request, $e);
    }
}
