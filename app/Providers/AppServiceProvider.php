<?php

namespace App\Providers;

use App\Domain\Backoffice\Category\Observers\CategoryObserver;
use App\Domain\Backoffice\Permission\Observers\PermissionObserver;
use App\Domain\Backoffice\Product\Observers\ProductObserver;
use App\Domain\Backoffice\ProductImage\Observers\ProductImageObserver;
use App\Domain\Backoffice\Role\Observers\RoleObserver;
use App\Domain\Backoffice\SalesOrder\Observers\SalesOrderObserver;
use App\Domain\Backoffice\SalesOrderItem\Observers\SalesOrderItemObserver;
use App\Domain\Backoffice\StockMovement\Observers\StockMovementObserver;
use App\Domain\Backoffice\User\Observers\UserObserver;
use App\Models\Category;
use App\Models\Permission;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Role;
use App\Models\SalesOrder;
use App\Models\SalesOrderItem;
use App\Models\StockMovement;
use App\Models\User;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Product::observe(ProductObserver::class);
        Category::observe(CategoryObserver::class);
        User::observe(UserObserver::class);
        StockMovement::observe(StockMovementObserver::class);
        SalesOrder::observe(SalesOrderObserver::class);
        SalesOrderItem::observe(SalesOrderItemObserver::class);
        ProductImage::observe(ProductImageObserver::class);
        Role::observe(RoleObserver::class);
        Permission::observe(PermissionObserver::class);
    }
}
