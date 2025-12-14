<?php

declare(strict_types=1);

namespace App\Domain\Backoffice\AuditLog\Enums;

enum AuditLogStatusType: string
{
    case SUCCESS = 'success';
    case FAILED = 'failed';

    public function label(): string
    {
        return match($this) {
            self::SUCCESS => 'Success',
            self::FAILED => 'Failed',
        };
    }

    public function isSuccess(): bool
    {
        return $this === self::SUCCESS;
    }

    public function isFailed(): bool
    {
        return $this === self::FAILED;
    }
}
