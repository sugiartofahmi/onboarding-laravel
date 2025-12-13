<?php

declare(strict_types=1);

namespace App\Infrastructure\Helpers;

use App\Infrastructure\Enums\HttpStatusCode;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\JsonResponse;

class ApiPaginationResponse extends ApiResponse
{
    public function __construct(
        private LengthAwarePaginator $paginator,
        private string $message = '',
        private HttpStatusCode $statusCode = HttpStatusCode::OK
    ) {}

    public function toResponse(): JsonResponse
    {
        return response()->json([
            'status_code' => $this->statusCode->value,
            'success' => true,
            'message' => $this->message,
            'data' => $this->paginator->items(),
            'meta' => [
                'total' => $this->paginator->total(),
                'per_page' => $this->paginator->perPage(),
                'current_page' => $this->paginator->currentPage(),
                'total_pages' => $this->paginator->lastPage(),
            ],
            'version' => $this->version,
        ], $this->statusCode->value);
    }
}
