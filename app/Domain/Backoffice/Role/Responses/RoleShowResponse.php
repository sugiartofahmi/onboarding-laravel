<?php

declare(strict_types=1);

namespace App\Domain\Backoffice\Role\Responses;

use App\Domain\Backoffice\Role\Messages\RoleMessage;
use App\Models\Role;
use Illuminate\Http\JsonResponse;

class RoleShowResponse
{
    public function __construct(
        private Role $role
    ) {}

    public function toJsonResponse(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => RoleMessage::SHOW_SUCCESS,
            'data' => $this->role,
        ]);
    }
}
