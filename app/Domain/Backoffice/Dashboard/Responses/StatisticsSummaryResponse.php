<?php

declare(strict_types=1);

namespace App\Domain\Backoffice\Dashboard\Responses;

use App\Models\StatisticsSummary;

class StatisticsSummaryResponse
{
    public function __construct(private StatisticsSummary $stats) {}

    public function toArray(): array
    {
        return [
            'total_products' => $this->stats->total_products,
            'total_categories' => $this->stats->total_categories,
            'total_orders' => $this->stats->total_orders,
            'total_revenue' => $this->stats->total_revenue,
            'low_stock_count' => $this->stats->low_stock_count,
        ];
    }
}
