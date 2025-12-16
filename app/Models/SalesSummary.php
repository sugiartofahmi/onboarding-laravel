<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class SalesSummary extends Model
{
    use HasUuids;

    protected $table = 'sales_summary';

    protected $fillable = [
        'sale_date',
        'total_orders',
        'total_revenue',
        'total_items_sold',
    ];

    protected function casts(): array
    {
        return [
            'sale_date' => 'date',
            'total_orders' => 'integer',
            'total_revenue' => 'decimal:2',
            'total_items_sold' => 'integer',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }
}
