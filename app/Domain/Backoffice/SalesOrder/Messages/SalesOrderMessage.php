<?php

declare(strict_types=1);

namespace App\Domain\Backoffice\SalesOrder\Messages;

class SalesOrderMessage
{
    public const INDEX_SUCCESS = 'Success get sales orders';
    public const SHOW_SUCCESS = 'Success get sales order detail';
    public const CREATE_SUCCESS = 'Sales order created successfully';
    public const UPDATE_SUCCESS = 'Sales order updated successfully';
}
