<?php

declare(strict_types=1);

namespace App\Domain\Backoffice\Dashboard\Messages;

class DashboardMessage
{
    public const STATS_SUCCESS = 'Statistics summary retrieved successfully';
    public const SALES_SUMMARY_SUCCESS = 'Sales summary retrieved successfully';
    public const LOW_STOCK_SUCCESS = 'Low stock products retrieved successfully';
}
