<?php

declare(strict_types=1);

namespace App\Domain\Backoffice\Permission\Responses;

use App\Domain\Backoffice\Permission\Messages\PermissionMessage;
use Illuminate\Http\JsonResponse;

class PermissionDeleteResponse
{
    public function __construct(
        private string $id
    ) {}

    public function toJsonResponse(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => PermissionMessage::DELETE_SUCCESS,
            'data' => [
                'id' => $this->id,
            ],
        ]);
    }
}
