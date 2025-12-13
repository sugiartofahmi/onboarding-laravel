<?php

declare(strict_types=1);

namespace App\Infrastructure\Helpers;

use App\Infrastructure\Enums\HttpStatusCode;
use Illuminate\Http\JsonResponse;

class ApiDataResponse extends ApiResponse
{
    public function __construct(
        private mixed $data = null,
        private string $message = '',
        private HttpStatusCode $statusCode = HttpStatusCode::OK
    ) {}

    public function toResponse(): JsonResponse
    {
        return response()->json([
            'status_code' => $this->statusCode->value,
            'success' => true,
            'message' => $this->message,
            'data' => $this->data,
            'version' => $this->version,
        ], $this->statusCode->value);
    }
}
