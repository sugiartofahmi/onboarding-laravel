<?php

declare(strict_types=1);

namespace App\Infrastructure\Helpers;

use App\Infrastructure\Enums\HttpStatusCode;
use Illuminate\Http\JsonResponse;

class ApiValidationErrorResponse extends ApiResponse
{
    public function __construct(
        private array $errors,
        private string $message = 'Validation failed',
        private HttpStatusCode $statusCode = HttpStatusCode::UNPROCESSABLE_ENTITY
    ) {}

    public function toResponse(): JsonResponse
    {
        return response()->json([
            'status_code' => $this->statusCode->value,
            'success' => false,
            'message' => $this->message,
            'errors' => $this->errors,
            'version' => $this->version,
        ], $this->statusCode->value);
    }
}
