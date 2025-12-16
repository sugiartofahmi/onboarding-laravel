<?php

declare(strict_types=1);

namespace App\Domain\Backoffice\Dashboard\Repositories;

use App\Models\StatisticsSummary;

class StatisticsSummaryQueryRepository
{
    public function __construct(private StatisticsSummary $model) {}

    public function findOne(): ?StatisticsSummary
    {
        return $this->model->first();
    }
}
