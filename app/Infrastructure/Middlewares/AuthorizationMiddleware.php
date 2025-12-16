<?php

declare(strict_types=1);

namespace App\Infrastructure\Middlewares;

use App\Domain\Backoffice\Role\Repositories\RoleQueryRepository;
use App\Infrastructure\Exceptions\BadRequestException;
use App\Infrastructure\Exceptions\ForbiddenException;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthorizationMiddleware
{
    private const EXCLUDED_ROUTES = [
        'api/v1/auth/role',
    ];

    public function __construct(
        private RoleQueryRepository $roleQueryRepository
    ) {}

    public function handle(Request $request, Closure $next): Response
    {
        if ($this->isExcluded($request)) {
            return $next($request);
        }

        $roleId = $request->header('x-role-id');

        if (!$roleId) {
            throw new BadRequestException('x-role-id header is required');
        }

        $user = JWTAuth::parseToken()->authenticate();

        $isExist = $this->roleQueryRepository->isExistByUserIdAndRoleId(
            $user->id,
            $roleId
        );

        if (!$isExist) {
            throw new ForbiddenException('User does not have the specified role');
        }

        $request->merge(['role_id' => $roleId]);

        return $next($request);
    }

    private function isExcluded(Request $request): bool
    {
        $path = $request->path();

        foreach (self::EXCLUDED_ROUTES as $excludedRoute) {
            if ($path === $excludedRoute) {
                return true;
            }
        }

        return false;
    }
}
