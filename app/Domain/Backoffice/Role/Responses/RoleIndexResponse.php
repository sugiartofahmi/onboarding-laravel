<?php

declare(strict_types=1);

namespace App\Domain\Backoffice\Role\Responses;

use App\Domain\Backoffice\Role\Messages\RoleMessage;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\JsonResponse;

class RoleIndexResponse
{
    public function __construct(
        private LengthAwarePaginator $roles
    ) {}

    public function toJsonResponse(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => RoleMessage::INDEX_SUCCESS,
            'data' => $this->roles->items(),
            'meta' => [
                'current_page' => $this->roles->currentPage(),
                'per_page' => $this->roles->perPage(),
                'total' => $this->roles->total(),
                'last_page' => $this->roles->lastPage(),
            ],
        ]);
    }
}
