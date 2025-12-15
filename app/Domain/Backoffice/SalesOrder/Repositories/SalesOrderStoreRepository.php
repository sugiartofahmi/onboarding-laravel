<?php

declare(strict_types=1);

namespace App\Domain\Backoffice\SalesOrder\Repositories;

use App\Models\SalesOrder;

class SalesOrderStoreRepository
{
    public function __construct(private SalesOrder $model) {}

    public function create(array $data): SalesOrder
    {
        return $this->model->create($data);
    }

    public function update(SalesOrder $model, array $data): SalesOrder
    {
        $model->update($data);
        return $model->fresh();
    }

    public function delete(SalesOrder $model): bool
    {
        return $model->delete();
    }
}
