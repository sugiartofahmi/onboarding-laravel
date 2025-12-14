<?php

declare(strict_types=1);

namespace App\Models;

use App\Domain\Backoffice\AuditLog\Enums\AuditLogActionType;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AuditLog extends Model
{
    use HasFactory;
    use HasUuids;

    protected $table = 'audit_logs';

    protected $fillable = [
        'user_id',
        'action',
        'description',
        'request_json',
        'ip_address',
        'user_agent',
        'request_url',
        'request_method',
    ];

    protected function casts(): array
    {
        return [
            'action' => AuditLogActionType::class,
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
