<?php

declare(strict_types=1);

namespace App\Infrastructure\Helpers;

use App\Infrastructure\Enums\HttpStatusCode;
use Illuminate\Http\JsonResponse;

class ApiErrorResponse extends ApiResponse
{
    public function __construct(
        private string $message,
        private HttpStatusCode $statusCode = HttpStatusCode::BAD_REQUEST
    ) {}

    public function toResponse(): JsonResponse
    {
        return response()->json([
            'status_code' => $this->statusCode->value,
            'success' => false,
            'message' => $this->message,
            'version' => $this->version,
        ], $this->statusCode->value);
    }
}
