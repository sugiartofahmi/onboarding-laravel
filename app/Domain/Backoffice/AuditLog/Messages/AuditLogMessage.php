<?php

declare(strict_types=1);

namespace App\Domain\Backoffice\AuditLog\Messages;

class AuditLogMessage
{
    public const INDEX_SUCCESS = 'Success get audit logs';
    public const SHOW_SUCCESS = 'Success get audit log detail';

    // Audit logs are typically created automatically via observers and services
    // Success messages may not be needed for this domain
}
