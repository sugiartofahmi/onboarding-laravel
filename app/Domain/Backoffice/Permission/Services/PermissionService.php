<?php

declare(strict_types=1);

namespace App\Domain\Backoffice\Permission\Services;

use App\Domain\Backoffice\Permission\Repositories\PermissionQueryRepository;
use App\Infrastructure\Exceptions\ForbiddenException;
use Illuminate\Support\Facades\Cache;

class PermissionService
{
    private const CACHE_PREFIX = 'permissions:role:';
    private const CACHE_TTL = 3600; // 1 hour

    public function __construct(
        private PermissionQueryRepository $permissionQueryRepository
    ) {}

    public function checkPermission(string $roleId, string $requiredPermission): void
    {
        $permissions = $this->getPermissionsByRoleIdWithCache($roleId);

        if (!in_array($requiredPermission, $permissions, true)) {
            throw new ForbiddenException("You don't have permission to access this resource");
        }
    }

    public function getPermissionsByRoleIdWithCache(string $roleId): array
    {
        $cacheKey = self::CACHE_PREFIX . $roleId;

        return Cache::remember($cacheKey, self::CACHE_TTL, function () use ($roleId) {
            $permissions = $this->permissionQueryRepository->findManyByRoleId($roleId);

            return $permissions->pluck('guard_name')->toArray();
        });
    }

    public function clearPermissionsCacheByRoleId(string $roleId): void
    {
        Cache::forget(self::CACHE_PREFIX . $roleId);
    }
}
