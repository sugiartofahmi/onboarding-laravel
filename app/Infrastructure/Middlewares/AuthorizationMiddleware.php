<?php

declare(strict_types=1);

namespace App\Infrastructure\Middlewares;

use App\Infrastructure\Exceptions\BadRequestException;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthorizationMiddleware
{
    private const EXCLUDED_ROUTES = [
        'api/v1/auth/role',
    ];

    public function handle(Request $request, Closure $next): Response
    {
        if ($this->isExcluded($request)) {
            return $next($request);
        }

        $roleId = $request->query('role_id');

        if (!$roleId) {
            throw new BadRequestException('role_id query parameter is required');
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
