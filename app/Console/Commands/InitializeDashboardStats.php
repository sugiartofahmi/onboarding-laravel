<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Domain\Backoffice\Product\Enums\ProductStatusType;
use App\Domain\Backoffice\SalesOrder\Enums\OrderStatusType;
use App\Models\Category;
use App\Models\Product;
use App\Models\SalesOrder;
use App\Models\SalesSummary;
use App\Models\StatisticsSummary;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class InitializeDashboardStats extends Command
{
    protected $signature = 'dashboard:init';

    protected $description = 'Initialize dashboard stats from existing data';

    public function handle(): int
    {
        $this->info('Initializing dashboard stats...');

        DB::transaction(function (): void {
            StatisticsSummary::truncate();
            SalesSummary::truncate();

            $totalProducts = Product::count();
            $totalCategories = Category::count();

            $paidOrders = SalesOrder::where('status', OrderStatusType::PAID->value);
            $totalOrders = $paidOrders->count();
            $totalRevenue = $paidOrders->sum('total_amount');

            $lowStockCount = Product::whereIn('status', [
                ProductStatusType::LOW_STOCK->value,
                ProductStatusType::OUT_OF_STOCK->value,
            ])->count();

            StatisticsSummary::create([
                'total_products' => $totalProducts,
                'total_categories' => $totalCategories,
                'total_orders' => $totalOrders,
                'total_revenue' => $totalRevenue,
                'low_stock_count' => $lowStockCount,
            ]);

            $this->info("Stats: Products={$totalProducts}, Categories={$totalCategories}, Orders={$totalOrders}, Revenue={$totalRevenue}");

            $salesSummary = SalesOrder::where('status', OrderStatusType::PAID->value)
                ->selectRaw('DATE(created_at) as sale_date')
                ->selectRaw('COUNT(*) as total_orders')
                ->selectRaw('SUM(total_amount) as total_revenue')
                ->groupBy('sale_date')
                ->get();

            foreach ($salesSummary as $day) {
                $itemsSold = SalesOrder::where('status', OrderStatusType::PAID->value)
                    ->whereDate('created_at', $day->sale_date)
                    ->with('items')
                    ->get()
                    ->sum(fn ($order) => $order->items->sum('quantity'));

                SalesSummary::create([
                    'sale_date' => $day->sale_date,
                    'total_orders' => $day->total_orders,
                    'total_revenue' => $day->total_revenue,
                    'total_items_sold' => $itemsSold,
                ]);
            }

            $this->info("Sales summary records created: {$salesSummary->count()}");
        });

        $this->info('Dashboard stats initialized successfully!');

        return Command::SUCCESS;
    }
}
