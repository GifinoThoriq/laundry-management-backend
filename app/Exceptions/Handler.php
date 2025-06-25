<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Throwable;

class Handler extends ExceptionHandler
{
    public function invalidJson($request, ValidationException $exception)
    {
        return response()->json([
            'meta' => [
                'status' => 422,
                'message' => 'Validation error',
            ],
            'errors' => $exception->errors(),
        ], 422);
    }

    // Optional: still include report() and render() methods
    public function register(): void
    {
        //
    }
}
