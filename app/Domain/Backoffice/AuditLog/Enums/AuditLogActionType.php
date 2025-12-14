<?php

declare(strict_types=1);

namespace App\Domain\Backoffice\AuditLog\Enums;

enum AuditLogActionType: string
{
    case CREATED = 'created';
    case UPDATED = 'updated';
    case DELETED = 'deleted';
    case VIEWED = 'viewed';
    case LOGIN = 'login';
    case LOGOUT = 'logout';

    public function label(): string
    {
        return match($this) {
            self::CREATED => 'Created',
            self::UPDATED => 'Updated',
            self::DELETED => 'Deleted',
            self::VIEWED => 'Viewed',
            self::LOGIN => 'Logged In',
            self::LOGOUT => 'Logged Out',
        };
    }

    public function isAuthEvent(): bool
    {
        return in_array($this, [self::LOGIN, self::LOGOUT]);
    }

    public function isModelEvent(): bool
    {
        return in_array($this, [self::CREATED, self::UPDATED, self::DELETED, self::VIEWED]);
    }
}
