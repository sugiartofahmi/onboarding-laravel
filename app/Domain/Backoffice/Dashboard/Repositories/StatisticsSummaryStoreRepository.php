<?php

declare(strict_types=1);

namespace App\Domain\Backoffice\Dashboard\Repositories;

use App\Models\StatisticsSummary;
use Illuminate\Support\Facades\DB;

class StatisticsSummaryStoreRepository
{
    public function __construct(private StatisticsSummary $model) {}

    public function create(): StatisticsSummary
    {
        return $this->model->create([
            'total_products' => 0,
            'total_categories' => 0,
            'total_orders' => 0,
            'total_revenue' => 0,
            'low_stock_count' => 0,
        ]);
    }

    public function incrementTotalProducts(int $count = 1): void
    {
        $this->model->first()?->increment('total_products', $count);
    }

    public function decrementTotalProducts(int $count = 1): void
    {
        $this->model->first()?->decrement('total_products', $count);
    }

    public function incrementTotalCategories(int $count = 1): void
    {
        $this->model->first()?->increment('total_categories', $count);
    }

    public function decrementTotalCategories(int $count = 1): void
    {
        $this->model->first()?->decrement('total_categories', $count);
    }

    public function incrementTotalOrdersAndRevenue(float $revenue): void
    {
        $stats = $this->model->first();
        if (!$stats) {
            return;
        }

        DB::transaction(function () use ($stats, $revenue): void {
            $stats->increment('total_orders');
            $stats->increment('total_revenue', $revenue);
        });
    }

    public function decrementTotalOrdersAndRevenue(float $revenue): void
    {
        $stats = $this->model->first();
        if (!$stats) {
            return;
        }

        DB::transaction(function () use ($stats, $revenue): void {
            $stats->decrement('total_orders');
            $stats->decrement('total_revenue', $revenue);
        });
    }

    public function incrementLowStockCount(): void
    {
        $this->model->first()?->increment('low_stock_count');
    }

    public function decrementLowStockCount(): void
    {
        $this->model->first()?->decrement('low_stock_count');
    }
}
