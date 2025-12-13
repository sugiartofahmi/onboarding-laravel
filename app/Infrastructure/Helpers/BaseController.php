<?php

declare(strict_types=1);

namespace App\Infrastructure\Helpers;

use App\Domain\Backoffice\Permission\Services\PermissionService;
use App\Infrastructure\Attributes\PermissionAttribute;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use ReflectionMethod;

class BaseController extends Controller
{
    public function callAction($method, $parameters)
    {
        $this->checkPermission($method);

        return parent::callAction($method, $parameters);
    }

    private function checkPermission(string $method): void
    {
        $reflection = new ReflectionMethod($this, $method);
        $attributes = $reflection->getAttributes(PermissionAttribute::class);

        if (empty($attributes)) {
            return;
        }

        $request = app(Request::class);
        $roleId = $request->query('role_id');

        if (!$roleId) {
            return;
        }

        $permissionService = app(PermissionService::class);

        foreach ($attributes as $attribute) {
            $permissionAttribute = $attribute->newInstance();
            $permissionService->checkPermission($roleId, $permissionAttribute->permission);
        }
    }
}
