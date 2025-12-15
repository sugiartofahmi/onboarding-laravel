<?php

declare(strict_types=1);

namespace App\Domain\Backoffice\Permission\Responses;

use App\Domain\Backoffice\Permission\Messages\PermissionMessage;
use App\Models\Permission;
use Illuminate\Http\JsonResponse;

class PermissionCreateResponse
{
    public function __construct(
        private Permission $permission
    ) {}

    public function toJsonResponse(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => PermissionMessage::CREATE_SUCCESS,
            'data' => $this->permission,
        ], 201);
    }
}
