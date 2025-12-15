<?php

declare(strict_types=1);

namespace App\Domain\API\Auth\Services;

use App\Domain\API\Auth\Messages\AuthErrorMessage;
use App\Domain\API\Auth\Requests\GetMeRequest;
use App\Domain\API\Auth\Requests\LoginRequest;
use App\Domain\API\Auth\Requests\RegisterRequest;
use App\Domain\API\Auth\Responses\GetMeResponse;
use App\Domain\API\Auth\Responses\GetRolesResponse;
use App\Domain\API\Auth\Responses\LoginResponse;
use App\Domain\API\Auth\Responses\RegisterResponse;
use App\Domain\Backoffice\Role\Constants\RoleConstant;
use App\Domain\Backoffice\Role\Repositories\RoleQueryRepository;
use App\Domain\Backoffice\User\Repositories\UserQueryRepository;
use App\Domain\Backoffice\User\Repositories\UserStoreRepository;
use App\Domain\Backoffice\AuditLog\Enums\AuditLogActionType;
use App\Domain\Backoffice\AuditLog\Services\AuditLogService;
use App\Infrastructure\Exceptions\BadRequestException;
use App\Infrastructure\Exceptions\UnauthenticatedException;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthService
{
    public function __construct(
        private UserQueryRepository $userQueryRepository,
        private UserStoreRepository $userStoreRepository,
        private RoleQueryRepository $roleQueryRepository,
        private AuditLogService $auditLogService
    ) {}

    public function register(RegisterRequest $request): RegisterResponse
    {
        try {
            $viewerRole = $this->roleQueryRepository->findOneByGuardName(RoleConstant::VIEWER);

            if (!$viewerRole) {
                throw new BadRequestException(AuthErrorMessage::REGISTER_FAILED);
            }

            $user = DB::transaction(function () use ($request, $viewerRole) {
                $user = $this->userStoreRepository->create($request->validated());
                $user->roles()->attach($viewerRole->id);

                return $user;
            });

            return new RegisterResponse($user);
        } catch (\Exception $e) {
            Log::error('Failed to register User', [
                'exception' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'timestamp' => now()->toDateTimeString(),
            ]);

            throw $e;
        }
    }

    public function login(LoginRequest $request): LoginResponse
    {
        $user = $this->userQueryRepository->findOneByEmail($request->email);

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw new UnauthenticatedException(AuthErrorMessage::INVALID_CREDENTIALS);
        }

        $roles = $this->roleQueryRepository->findManyByUserId($user->id);
        $token = $this->createToken($user);

        $this->auditLogService->logAuthEvent(
            action: AuditLogActionType::LOGIN,
            description: "User Logged In: {$user->name} ({$user->email})"
        );

        return new LoginResponse($user, $token, $roles);
    }

    public function getRoles(User $user): GetRolesResponse
    {
        $roles = $this->roleQueryRepository->findManyByUserId($user->id);

        return new GetRolesResponse($roles);
    }

    public function logout(): void
    {
        $user = JWTAuth::user();

        $this->auditLogService->logAuthEvent(
            action: AuditLogActionType::LOGOUT,
            description: "User Logged Out: {$user->name} ({$user->email})"
        );

        JWTAuth::invalidate(JWTAuth::getToken());
    }

    public function me(GetMeRequest $request): GetMeResponse
    {
        return new GetMeResponse(JWTAuth::user());
    }

    private function createToken(User $user): string
    {
        $customClaims = [
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ],
        ];

        return JWTAuth::customClaims($customClaims)->fromUser($user);
    }
}
