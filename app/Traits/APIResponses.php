<?php

declare(strict_types=1);

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

trait APIResponses
{
    public function ok(string $message, mixed $data = [], $statusCode = 200): JsonResponse
    {
        return $this->success($message, $data, $statusCode);
    }

    protected function success(string $message, mixed $data = [], int|string $statusCode = 200): JsonResponse
    {
        return response()->json([
            'status' => $statusCode,
            'message' => $message,
            'data' => $data,
        ],$statusCode);
    }

    protected function error(string $message, int|string $statusCode): JsonResponse
    {
        $statusCode = $statusCode === 0 ? Response::HTTP_UNPROCESSABLE_ENTITY : $statusCode;

        return response()->json([
            'status' => $statusCode,
            'message' => $message,
        ],$statusCode);
    }
}
