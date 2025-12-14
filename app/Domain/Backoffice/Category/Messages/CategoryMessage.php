<?php

declare(strict_types=1);

namespace App\Domain\Backoffice\Category\Messages;

class CategoryMessage
{
    public const NOT_FOUND = 'Category not found';
    public const CREATE_SUCCESS = 'Category created successfully';
    public const UPDATE_SUCCESS = 'Category updated successfully';
    public const DELETE_SUCCESS = 'Category deleted successfully';
}
