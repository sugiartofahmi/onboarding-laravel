<?php

declare(strict_types=1);

namespace App\Domain\Backoffice\Permission\Constants;

class PermissionConstant
{
    // Category
    public const CREATE_CATEGORY = 'create_category';
    public const READ_CATEGORY = 'read_category';
    public const UPDATE_CATEGORY = 'update_category';
    public const DELETE_CATEGORY = 'delete_category';

    // Product
    public const CREATE_PRODUCT = 'create_product';
    public const READ_PRODUCT = 'read_product';
    public const UPDATE_PRODUCT = 'update_product';
    public const DELETE_PRODUCT = 'delete_product';

    // Stock Movement
    public const CREATE_STOCK_MOVEMENT = 'create_stock_movement';
    public const READ_STOCK_MOVEMENT = 'read_stock_movement';
    public const UPDATE_STOCK_MOVEMENT = 'update_stock_movement';
    public const DELETE_STOCK_MOVEMENT = 'delete_stock_movement';

    // Sales Order
    public const CREATE_SALES_ORDER = 'create_sales_order';
    public const READ_SALES_ORDER = 'read_sales_order';
    public const UPDATE_SALES_ORDER = 'update_sales_order';
    public const DELETE_SALES_ORDER = 'delete_sales_order';

    // User
    public const CREATE_USER = 'create_user';
    public const READ_USER = 'read_user';
    public const UPDATE_USER = 'update_user';
    public const DELETE_USER = 'delete_user';
}
