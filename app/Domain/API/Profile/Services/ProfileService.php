<?php

declare(strict_types=1);

namespace App\Domain\API\Profile\Services;

use App\Domain\API\Profile\Repositories\ProfileRepository;
use App\Domain\API\Profile\Requests\ProfileUpdateRequest;
use App\Domain\API\Profile\Responses\ProfileShowResponse;
use App\Domain\API\Profile\Responses\ProfileUpdateResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Tymon\JWTAuth\Facades\JWTAuth;

class ProfileService
{
    public function __construct(
        private ProfileRepository $profileRepository
    ) {}

    public function show(): ProfileShowResponse
    {
        $user = JWTAuth::user();
        $userWithRoles = $this->profileRepository->findById($user->id);

        return new ProfileShowResponse($userWithRoles);
    }

    public function update(ProfileUpdateRequest $request): ProfileUpdateResponse
    {
        try {
            $user = JWTAuth::user();

            $data = array_filter([
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'password' => $request->filled('password')
                    ? Hash::make($request->input('password'))
                    : null,
            ], fn ($value) => $value !== null);

            $updatedUser = $this->profileRepository->update($user, $data);

            return new ProfileUpdateResponse($updatedUser);
        } catch (\Exception $e) {
            Log::error('Failed to update Profile', [
                'entity_id' => $user->id ?? null,
                'exception' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'timestamp' => now()->toDateTimeString(),
            ]);

            throw $e;
        }
    }
}
