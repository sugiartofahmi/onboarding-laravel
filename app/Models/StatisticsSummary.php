<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class StatisticsSummary extends Model
{
    use HasUuids;

    protected $table = 'statistics_summary';

    protected $fillable = [
        'total_products',
        'total_categories',
        'total_orders',
        'total_revenue',
        'low_stock_count',
    ];

    protected function casts(): array
    {
        return [
            'total_products' => 'integer',
            'total_categories' => 'integer',
            'total_orders' => 'integer',
            'total_revenue' => 'decimal:2',
            'low_stock_count' => 'integer',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }
}
