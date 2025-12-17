<?php

declare(strict_types=1);

namespace App\Infrastructure\Middlewares;

use App\Infrastructure\Enums\HttpStatusCode;
use App\Infrastructure\Exceptions\BadRequestException;
use App\Infrastructure\Exceptions\BusinessException;
use App\Infrastructure\Exceptions\DataDataNotFoundException;
use App\Infrastructure\Exceptions\ForbiddenException;
use App\Infrastructure\Exceptions\IntegrationException;
use App\Infrastructure\Exceptions\InternalServiceException;
use App\Infrastructure\Exceptions\NotAllowedException;
use App\Infrastructure\Exceptions\ServiceUnavailableException;
use App\Infrastructure\Exceptions\UnauthenticatedException;
use App\Infrastructure\Exceptions\UnprocessableEntityException;
use App\Infrastructure\Exceptions\ValidationException as CustomValidationException;
use App\Infrastructure\Helpers\ApiErrorResponse;
use App\Infrastructure\Helpers\ApiValidationErrorResponse;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Throwable;

class ExceptionHandler
{
    public static function render(Throwable $e, Request $request): ?JsonResponse
    {
        // Always return JSON for API routes
        if (!$request->expectsJson() && !$request->is('api/*', 'backoffice/*')) {
            return null;
        }

        // Validation errors - return errors array only
        if (self::isValidationException($e)) {
            return self::renderValidationError($e);
        }

        // Business errors - return message only
        return self::renderBusinessError($e);
    }

    private static function isValidationException(Throwable $e): bool
    {
        return $e instanceof ValidationException
            || $e instanceof CustomValidationException
            || $e instanceof UnprocessableEntityException;
    }

    private static function renderValidationError(Throwable $e): JsonResponse
    {
        $errors = match (true) {
            $e instanceof ValidationException => $e->errors(),
            $e instanceof CustomValidationException => $e->getErrors(),
            $e instanceof UnprocessableEntityException => $e->getErrors(),
            default => [],
        };

        return (new ApiValidationErrorResponse(
            errors: $errors,
            statusCode: HttpStatusCode::UNPROCESSABLE_ENTITY
        ))->toResponse();
    }

    private static function renderBusinessError(Throwable $e): JsonResponse
    {
        $statusCode = self::getStatusCode($e);
        $message = self::getMessage($e, $statusCode);

        return (new ApiErrorResponse(
            message: $message,
            statusCode: $statusCode
        ))->toResponse();
    }

    private static function getStatusCode(Throwable $e): HttpStatusCode
    {
        return match (true) {
            $e instanceof BadRequestException,
            $e instanceof BusinessException => HttpStatusCode::BAD_REQUEST,
            $e instanceof DataDataNotFoundException => HttpStatusCode::NOT_FOUND,
            $e instanceof UnauthenticatedException,
            $e instanceof AuthenticationException => HttpStatusCode::UNAUTHORIZED,
            $e instanceof ForbiddenException,
            $e instanceof NotAllowedException => HttpStatusCode::FORBIDDEN,
            $e instanceof IntegrationException => HttpStatusCode::BAD_GATEWAY,
            $e instanceof ServiceUnavailableException => HttpStatusCode::SERVICE_UNAVAILABLE,
            $e instanceof InternalServiceException => HttpStatusCode::INTERNAL_SERVER_ERROR,
            $e instanceof QueryException => self::getQueryExceptionStatusCode($e),
            default => HttpStatusCode::INTERNAL_SERVER_ERROR,
        };
    }

    private static function getQueryExceptionStatusCode(QueryException $e): HttpStatusCode
    {
        $errorCode = $e->errorInfo[0] ?? null;

        return match ($errorCode) {
            '23505' => HttpStatusCode::CONFLICT, // unique_violation
            '23503' => HttpStatusCode::BAD_REQUEST, // foreign_key_violation
            default => HttpStatusCode::INTERNAL_SERVER_ERROR,
        };
    }

    private static function getMessage(Throwable $e, HttpStatusCode $statusCode): string
    {
        // Handle QueryException dengan pesan yang lebih user-friendly
        if ($e instanceof QueryException) {
            $errorCode = $e->errorInfo[0] ?? null;

            return match ($errorCode) {
                '23505' => 'A record with this value already exists.',
                '23503' => 'Referenced record does not exist.',
                default => self::getDefaultMessage($statusCode),
            };
        }

        // Untuk production, sembunyikan error internal
        if ($statusCode === HttpStatusCode::INTERNAL_SERVER_ERROR && !config('app.debug')) {
            return 'An unexpected error occurred.';
        }

        return $e->getMessage();
    }

    private static function getDefaultMessage(HttpStatusCode $statusCode): string
    {
        return match ($statusCode) {
            HttpStatusCode::BAD_REQUEST => 'Bad request.',
            HttpStatusCode::UNAUTHORIZED => 'Unauthenticated.',
            HttpStatusCode::FORBIDDEN => 'Access forbidden.',
            HttpStatusCode::NOT_FOUND => 'Resource not found.',
            HttpStatusCode::CONFLICT => 'Resource already exists.',
            HttpStatusCode::UNPROCESSABLE_ENTITY => 'Validation failed.',
            HttpStatusCode::BAD_GATEWAY => 'Integration error.',
            HttpStatusCode::SERVICE_UNAVAILABLE => 'Service unavailable.',
            HttpStatusCode::INTERNAL_SERVER_ERROR => 'An unexpected error occurred.',
            default => 'An error occurred.',
        };
    }
}
