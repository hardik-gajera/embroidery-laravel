<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ApiResponse
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // Only apply to API routes
        if (!$request->is('api/*')) {
            return $response;
        }

        // Skip processing for binary file responses (downloads)
        if ($response instanceof BinaryFileResponse) {
            return $response;
        }

        // Handle validation errors
        if ($response->status() === 422 && $response instanceof JsonResponse) {
            $data = $response->getData(true);
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $data['errors'] ?? $data
            ], 422);
        }

        // Handle authentication errors
        if ($response->status() === 401) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated'
            ], 401);
        }

        // Handle authorization errors
        if ($response->status() === 403) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        return $response;
    }
}