<?php

declare(strict_types=1);

namespace App\Domain\Backoffice\StockMovement\Repositories;

use App\Models\StockMovement;

class StockMovementStoreRepository
{
    public function __construct(private StockMovement $model) {}

    public function create(array $data): StockMovement
    {
        return $this->model->create($data);
    }

    public function update(StockMovement $model, array $data): StockMovement
    {
        $model->update($data);
        return $model->fresh();
    }

    public function delete(StockMovement $model): bool
    {
        return $model->delete();
    }
}
