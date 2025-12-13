<?php

declare(strict_types=1);

namespace App\Domain\Backoffice\User\Repositories;

use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class UserQueryRepository
{
    public function __construct(private User $model) {}

    public function index(int $perPage = 10, array $filters = []): LengthAwarePaginator
    {
        $query = $this->model->query();

        if (isset($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('name', 'like', "%{$filters['search']}%")
                  ->orWhere('email', 'like', "%{$filters['search']}%");
            });
        }

        return $query->paginate($perPage);
    }

    public function findOneById(string $id): ?User
    {
        return $this->model->find($id);
    }

    public function findOneByEmail(string $email): ?User
    {
        return $this->model->where('email', $email)->first();
    }

    public function findMany(): Collection
    {
        return $this->model->all();
    }
}
