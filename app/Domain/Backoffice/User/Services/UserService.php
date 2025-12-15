<?php

declare(strict_types=1);

namespace App\Domain\Backoffice\User\Services;

use App\Domain\Backoffice\User\Messages\UserErrorMessage;
use App\Domain\Backoffice\User\Repositories\UserQueryRepository;
use App\Domain\Backoffice\User\Repositories\UserStoreRepository;
use App\Domain\Backoffice\User\Requests\UserCreateRequest;
use App\Domain\Backoffice\User\Requests\UserIndexRequest;
use App\Domain\Backoffice\User\Requests\UserUpdateRequest;
use App\Domain\Backoffice\User\Responses\UserCreateResponse;
use App\Domain\Backoffice\User\Responses\UserDeleteResponse;
use App\Domain\Backoffice\User\Responses\UserIndexResponse;
use App\Domain\Backoffice\User\Responses\UserShowResponse;
use App\Domain\Backoffice\User\Responses\UserUpdateResponse;
use App\Domain\Backoffice\AuditLog\Enums\AuditLogActionType;
use App\Domain\Backoffice\AuditLog\Services\AuditLogService;
use App\Infrastructure\Exceptions\NotFoundException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class UserService
{
    public function __construct(
        private UserQueryRepository $userQueryRepository,
        private UserStoreRepository $userStoreRepository,
        private AuditLogService $auditLogService
    ) {}

    public function index(UserIndexRequest $request): UserIndexResponse
    {
        $users = $this->userQueryRepository->index($request);

        $this->auditLogService->logViewEvent(
            action: AuditLogActionType::VIEWED,
            description: 'Viewed User List'
        );

        return new UserIndexResponse($users);
    }

    public function show(string $id): UserShowResponse
    {
        $user = $this->userQueryRepository->findOneByIdWithRoles($id);

        if (!$user) {
            throw new NotFoundException(UserErrorMessage::NOT_FOUND);
        }

        $this->auditLogService->logViewEvent(
            action: AuditLogActionType::VIEWED,
            description: "Viewed User: {$user->id}"
        );

        return new UserShowResponse($user);
    }

    public function create(UserCreateRequest $request): UserCreateResponse
    {
        try {
            $user = DB::transaction(function () use ($request) {
                $user = $this->userStoreRepository->create([
                    'name' => $request->input('name'),
                    'email' => $request->input('email'),
                    'password' => Hash::make($request->input('password')),
                ]);

                // Attach roles
                if ($request->filled('role_ids')) {
                    $user->roles()->attach($request->input('role_ids'));
                }

                return $user->fresh(['roles']);
            });

            return new UserCreateResponse($user);
        } catch (\Exception $e) {
            Log::error('Failed to create User', [
                'exception' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'timestamp' => now()->toDateTimeString(),
            ]);

            throw $e;
        }
    }

    public function update(string $id, UserUpdateRequest $request): UserUpdateResponse
    {
        try {
            $user = $this->userQueryRepository->findOneById($id);

            if (!$user) {
                throw new NotFoundException(UserErrorMessage::NOT_FOUND);
            }

            $user = DB::transaction(function () use ($user, $request) {
                $data = array_filter([
                    'name' => $request->input('name'),
                    'email' => $request->input('email'),
                    'password' => $request->filled('password')
                        ? Hash::make($request->input('password'))
                        : null,
                ], fn ($value) => $value !== null);

                $user = $this->userStoreRepository->update($user, $data);

                // Sync roles
                if ($request->filled('role_ids')) {
                    $user->roles()->sync($request->input('role_ids'));
                }

                return $user->fresh(['roles']);
            });

            return new UserUpdateResponse($user);
        } catch (\Exception $e) {
            Log::error('Failed to update User', [
                'entity_id' => $id,
                'exception' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'timestamp' => now()->toDateTimeString(),
            ]);

            throw $e;
        }
    }

    public function delete(string $id): UserDeleteResponse
    {
        try {
            $user = $this->userQueryRepository->findOneById($id);

            if (!$user) {
                throw new NotFoundException(UserErrorMessage::NOT_FOUND);
            }

            $this->userStoreRepository->delete($user);

            return new UserDeleteResponse($id);
        } catch (\Exception $e) {
            Log::error('Failed to delete User', [
                'entity_id' => $id,
                'exception' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'timestamp' => now()->toDateTimeString(),
            ]);

            throw $e;
        }
    }
}
