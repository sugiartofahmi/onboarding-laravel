<?php

declare(strict_types=1);

namespace App\Domain\Backoffice\Permission\Responses;

use App\Domain\Backoffice\Permission\Messages\PermissionMessage;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\JsonResponse;

class PermissionIndexResponse
{
    public function __construct(
        private LengthAwarePaginator $permissions
    ) {}

    public function toJsonResponse(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => PermissionMessage::INDEX_SUCCESS,
            'data' => $this->permissions->items(),
            'meta' => [
                'current_page' => $this->permissions->currentPage(),
                'per_page' => $this->permissions->perPage(),
                'total' => $this->permissions->total(),
                'last_page' => $this->permissions->lastPage(),
            ],
        ]);
    }
}
