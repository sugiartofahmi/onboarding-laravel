<?php

declare(strict_types=1);

namespace App\Domain\Backoffice\Dashboard\Repositories;

use App\Models\SalesSummary;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class SalesSummaryStoreRepository
{
    public function __construct(private SalesSummary $model) {}

    public function incrementByDate(Carbon $date, float $revenue, int $itemsSold): void
    {
        $record = $this->model->firstOrCreate(
            ['sale_date' => $date->toDateString()],
            [
                'total_orders' => 0,
                'total_revenue' => 0,
                'total_items_sold' => 0,
            ]
        );

        DB::transaction(function () use ($record, $revenue, $itemsSold): void {
            $record->increment('total_orders');
            $record->increment('total_revenue', $revenue);
            $record->increment('total_items_sold', $itemsSold);
        });
    }

    public function decrementByDate(Carbon $date, float $revenue, int $itemsSold): void
    {
        $record = $this->model->where('sale_date', $date->toDateString())->first();

        if (!$record) {
            return;
        }

        DB::transaction(function () use ($record, $revenue, $itemsSold): void {
            $record->decrement('total_orders');
            $record->decrement('total_revenue', $revenue);
            $record->decrement('total_items_sold', $itemsSold);
        });
    }
}
