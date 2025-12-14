<?php

declare(strict_types=1);

namespace App\Domain\Backoffice\AuditLog\Responses;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class AuditLogIndexResponse
{
    public function __construct(
        private LengthAwarePaginator $auditLogs
    ) {}

    public function toArray(): array
    {
        return $this->auditLogs->map(function ($log) {
            return [
                'id' => $log->id,
                'user' => $log->user ? [
                    'id' => $log->user->id,
                    'name' => $log->user->name,
                    'email' => $log->user->email,
                ] : null,
                'action' => $log->action->value,
                'action_label' => $log->action->label(),
                'description' => $log->description,
                'request_json' => $log->request_json,
                'ip_address' => $log->ip_address,
                'user_agent' => $log->user_agent,
                'request_url' => $log->request_url,
                'request_method' => $log->request_method,
                'created_at' => $log->created_at->toISOString(),
            ];
        })->toArray();
    }

    public function getPaginator(): LengthAwarePaginator
    {
        return $this->auditLogs;
    }
}
