<?php

declare(strict_types=1);

namespace App\Domain\Backoffice\Product\Messages;

class ProductMessage
{
    public const INDEX_SUCCESS = 'Success get products';
    public const SHOW_SUCCESS = 'Success get product detail';
    public const CREATE_SUCCESS = 'Product created successfully';
    public const UPDATE_SUCCESS = 'Product updated successfully';
    public const DELETE_SUCCESS = 'Product deleted successfully';
}
