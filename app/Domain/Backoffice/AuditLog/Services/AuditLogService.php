<?php

declare(strict_types=1);

namespace App\Domain\Backoffice\AuditLog\Services;

use App\Domain\Backoffice\AuditLog\Enums\AuditLogActionType;
use App\Domain\Backoffice\AuditLog\Repositories\AuditLogQueryRepository;
use App\Domain\Backoffice\AuditLog\Repositories\AuditLogStoreRepository;
use App\Domain\Backoffice\AuditLog\Requests\AuditLogIndexRequest;
use App\Domain\Backoffice\AuditLog\Responses\AuditLogIndexResponse;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuditLogService
{
    public function __construct(
        private AuditLogQueryRepository $auditLogQueryRepository,
        private AuditLogStoreRepository $auditLogStoreRepository
    ) {}

    public function index(AuditLogIndexRequest $request): AuditLogIndexResponse
    {
        $auditLogs = $this->auditLogQueryRepository->index($request);

        $this->logViewEvent(
            action: AuditLogActionType::VIEWED,
            description: 'Viewed Audit Log List'
        );

        return new AuditLogIndexResponse($auditLogs);
    }

    public function logModelEvent(
        Model $model,
        AuditLogActionType $action,
        ?string $description = null
    ): void {
        $this->createLog(
            action: $action,
            description: $description ?? $this->generateDescription($model, $action)
        );
    }

    public function logAuthEvent(
        AuditLogActionType $action,
        ?string $description = null
    ): void {
        $this->createLog(
            action: $action,
            description: $description ?? "User {$action->label()}"
        );
    }

    public function logViewEvent(
        AuditLogActionType $action,
        string $description
    ): void {
        $this->createLog(
            action: $action,
            description: $description
        );
    }

    private function createLog(
        AuditLogActionType $action,
        ?string $description = null
    ): void {
        $this->auditLogStoreRepository->create([
            'user_id' => $this->getCurrentUserId(),
            'action' => $action,
            'description' => $description,
            'request_json' => $this->getRequestJson(),
            'ip_address' => $this->getIpAddress(),
            'user_agent' => $this->getUserAgent(),
            'request_url' => $this->getRequestUrl(),
            'request_method' => $this->getRequestMethod(),
        ]);
    }

    private function generateDescription(Model $model, AuditLogActionType $action): string
    {
        $modelName = class_basename($model);
        $actionLabel = $action->label();

        return "{$actionLabel} {$modelName}";
    }

    private function getRequestJson(): ?string
    {
        try {
            $request = Request::instance();
            $payload = $request->except(['password', 'password_confirmation', 'token']);

            if (empty($payload)) {
                return null;
            }

            return json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        } catch (\Exception $e) {
            return null;
        }
    }

    private function getCurrentUserId(): ?string
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            return $user?->id;
        } catch (\Exception $e) {
            return null;
        }
    }

    private function getIpAddress(): ?string
    {
        try {
            return Request::ip();
        } catch (\Exception $e) {
            return null;
        }
    }

    private function getUserAgent(): ?string
    {
        try {
            return Request::userAgent();
        } catch (\Exception $e) {
            return null;
        }
    }

    private function getRequestUrl(): ?string
    {
        try {
            $url = Request::fullUrl();
            return substr($url, 0, 500);
        } catch (\Exception $e) {
            return null;
        }
    }

    private function getRequestMethod(): ?string
    {
        try {
            return Request::method();
        } catch (\Exception $e) {
            return null;
        }
    }
}
