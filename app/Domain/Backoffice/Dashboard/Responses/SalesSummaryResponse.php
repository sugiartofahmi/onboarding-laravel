<?php

declare(strict_types=1);

namespace App\Domain\Backoffice\Dashboard\Responses;

use Illuminate\Support\Collection;

class SalesSummaryResponse
{
    public function __construct(private Collection $salesSummary) {}

    public function toArray(): array
    {
        return $this->salesSummary->map(fn ($sale) => [
            'date' => $sale->sale_date->toDateString(),
            'total_orders' => $sale->total_orders,
            'total_revenue' => $sale->total_revenue,
            'total_items_sold' => $sale->total_items_sold,
        ])->toArray();
    }
}
