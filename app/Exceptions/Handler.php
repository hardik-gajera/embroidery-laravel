<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Throwable;

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

    /**
     * Render an exception into an HTTP response.
     */
    public function render($request, Throwable $e): Response|JsonResponse
    {
        // Handle mobile API routes with JSON responses
        if ($request->is('api/mobile/*')) {
            return $this->handleMobileApiException($request, $e);
        }

        return parent::render($request, $e);
    }

    /**
     * Handle exceptions for mobile API routes
     */
    protected function handleMobileApiException(Request $request, Throwable $e): JsonResponse
    {
        // Validation Exception
        if ($e instanceof ValidationException) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->validator->errors()->all(),
                'field_errors' => $e->validator->errors()->toArray()
            ], 422);
        }

        // Authentication Exception
        if ($e instanceof AuthenticationException) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated',
                'error' => 'Invalid or missing authentication token'
            ], 401);
        }

        // Model Not Found
        if ($e instanceof ModelNotFoundException) {
            return response()->json([
                'success' => false,
                'message' => 'Resource not found',
                'error' => 'The requested resource could not be found'
            ], 404);
        }

        // Route Not Found
        if ($e instanceof NotFoundHttpException) {
            return response()->json([
                'success' => false,
                'message' => 'Endpoint not found',
                'error' => 'The requested API endpoint does not exist'
            ], 404);
        }

        // Method Not Allowed
        if ($e instanceof MethodNotAllowedHttpException) {
            return response()->json([
                'success' => false,
                'message' => 'Method not allowed',
                'error' => 'The HTTP method is not supported for this endpoint'
            ], 405);
        }

        // Server Error (500)
        $statusCode = method_exists($e, 'getStatusCode') ? $e->getStatusCode() : 500;
        
        return response()->json([
            'success' => false,
            'message' => $statusCode >= 500 ? 'Internal server error' : 'An error occurred',
            'error' => config('app.debug') ? $e->getMessage() : 'Something went wrong'
        ], $statusCode >= 100 && $statusCode < 600 ? $statusCode : 500);
    }
}
