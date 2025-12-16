<?php

declare(strict_types=1);

namespace App\Domain\Backoffice\Dashboard\Repositories;

use App\Domain\Backoffice\Dashboard\Requests\SalesSummaryRequest;
use App\Models\SalesSummary;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class SalesSummaryQueryRepository
{
    public function __construct(private SalesSummary $model) {}

    public function findByDateRange(SalesSummaryRequest $request): Collection
    {
        $startDate = $request->input('start_date', Carbon::now()->subDays(30)->toDateString());
        $endDate = $request->input('end_date', Carbon::today()->toDateString());

        return $this->model
            ->whereBetween('sale_date', [$startDate, $endDate])
            ->orderBy('sale_date', 'asc')
            ->get();
    }
}
