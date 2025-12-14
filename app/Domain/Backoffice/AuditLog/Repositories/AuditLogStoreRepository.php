<?php

declare(strict_types=1);

namespace App\Domain\Backoffice\AuditLog\Repositories;

use App\Domain\Backoffice\AuditLog\Enums\AuditLogActionType;
use App\Models\AuditLog;
use Illuminate\Support\Facades\Log;

class AuditLogStoreRepository
{
    public function __construct(private AuditLog $model) {}

    public function create(array $data): ?AuditLog
    {
        try {
            return $this->model->create($data);
        } catch (\Exception $e) {
            Log::warning('Failed to create audit log', [
                'action' => $data['action'] ?? null,
                'error' => $e->getMessage(),
            ]);
            return null;
        }
    }
}
