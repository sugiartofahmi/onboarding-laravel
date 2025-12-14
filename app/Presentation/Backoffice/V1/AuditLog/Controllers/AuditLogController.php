<?php

declare(strict_types=1);

namespace App\Presentation\Backoffice\V1\AuditLog\Controllers;

use App\Domain\Backoffice\AuditLog\Requests\AuditLogIndexRequest;
use App\Domain\Backoffice\AuditLog\Services\AuditLogService;
use App\Domain\Backoffice\Permission\Constants\PermissionConstant;
use App\Infrastructure\Attributes\PermissionAttribute;
use App\Infrastructure\Enums\HttpStatusCode;
use App\Infrastructure\Helpers\ApiPaginationResponse;
use App\Infrastructure\Helpers\BaseController;
use Illuminate\Http\JsonResponse;

class AuditLogController extends BaseController
{
    public function __construct(
        private AuditLogService $auditLogService
    ) {}

    #[PermissionAttribute(PermissionConstant::READ_AUDIT_LOG)]
    public function index(AuditLogIndexRequest $request): JsonResponse
    {
        $response = $this->auditLogService->index($request);

        return (new ApiPaginationResponse(
            data: $response->toArray(),
            paginator: $response->getPaginator(),
            message: '',
            statusCode: HttpStatusCode::OK
        ))->toResponse();
    }
}
