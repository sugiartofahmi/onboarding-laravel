<?php

declare(strict_types=1);

namespace App\Domain\Backoffice\Role\Responses;

use App\Domain\Backoffice\Role\Messages\RoleMessage;
use Illuminate\Http\JsonResponse;

class RoleDeleteResponse
{
    public function __construct(
        private string $id
    ) {}

    public function toJsonResponse(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => RoleMessage::DELETE_SUCCESS,
            'data' => [
                'id' => $this->id,
            ],
        ]);
    }
}
