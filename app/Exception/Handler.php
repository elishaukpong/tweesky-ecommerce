<?php

declare(strict_types=1);

namespace App\Exception;

use App\Traits\APIResponses;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class Handler extends ExceptionHandler
{
    use APIResponses;

    public function render($request, Throwable $e)
    {
        if ($request->expectsJson()) {
            if ($e instanceof ModelNotFoundException) {
                return $this->error($e->getMessage(), $e->getCode());
            }

            if ($e instanceof AuthorizationException || $e instanceof AuthenticationException) {
                return $this->error($e->getMessage(), Response::HTTP_UNAUTHORIZED);
            }

            return $this->error($e->getMessage(), $e->getCode());
        }

        return parent::render($request, $e);
    }

    protected function invalidJson($request, ValidationException $exception)
    {
        return $this->error($exception->getMessage(), $exception->status, $exception->errors());
    }
}
