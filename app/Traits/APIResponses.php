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

    public function created(string $message, mixed $data = []): JsonResponse
    {
        return $this->success($message, $data, Response::HTTP_CREATED);
    }

    protected function success(string $message, mixed $data = [], int|string $statusCode = 200): JsonResponse
    {
        return response()->json([
            'status' => $statusCode,
            'message' => $message,
            'data' => $data,
        ],$statusCode);
    }

    protected function error(string $message, int|string $statusCode, mixed $errors = []): JsonResponse
    {
        $statusCode = $statusCode === 0 ? Response::HTTP_UNPROCESSABLE_ENTITY : $statusCode;

        return response()->json(array_filter([
            'status' => $statusCode,
            'message' => $message,
            'errors' => $errors
        ]),$statusCode);
    }
}
