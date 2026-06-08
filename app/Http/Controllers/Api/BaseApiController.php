<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Throwable;

class BaseApiController extends Controller
{
    /**
     * Success response helper
     */
    protected function successResponse(string $message = 'Success', array $data = [], int $statusCode = 200): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data
        ], $statusCode);
    }

    /**
     * Error response helper
     */
    protected function errorResponse(string $message, array $errors = [], int $statusCode = 400): JsonResponse
    {
        $response = [
            'success' => false,
            'message' => $message
        ];

        if (!empty($errors)) {
            $response['errors'] = $errors;
        }

        return response()->json($response, $statusCode);
    }

    /**
     * Validation error response
     */
    protected function validationErrorResponse(array $errors): JsonResponse
    {
        $firstError = collect($errors)->flatten()->first();

        return response()->json([
            'success' => false,
            'message' => $firstError ?? 'Validation failed',
        ], 422);
    }

    /**
     * Handle controller exceptions
     */
    protected function handleException(Throwable $e, string $context = 'Operation'): JsonResponse
    {
        Log::error("API Error in {$context}: " . $e->getMessage(), [
            'exception' => $e,
            'trace' => $e->getTraceAsString()
        ]);

        return $this->errorResponse(
            'An error occurred while processing your request',
            [],
            500
        );
    }
}