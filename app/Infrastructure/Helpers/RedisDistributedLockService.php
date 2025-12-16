<?php

declare(strict_types=1);

namespace App\Infrastructure\Helpers;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class RedisDistributedLockService
{
    private const LOCK_EXPIRATION_SECONDS = 10;
    private const LOCK_KEY_PREFIX = 'distributed_lock';

    private bool $isEnabled;

    public function __construct()
    {
        $this->isEnabled = config('cache.default') === 'redis' && env('REDIS_LOCK_ENABLED', true);
    }

    public function acquireLock(string $lockKey, int $expirationSeconds = self::LOCK_EXPIRATION_SECONDS): bool
    {
        if (!$this->isEnabled) {
            return true;
        }

        $fullKey = $this->buildFullKey($lockKey);
        $acquired = Cache::lock($fullKey, $expirationSeconds)->get();

        Log::info($acquired ? 'Lock acquired' : 'Lock acquisition failed', ['lock_key' => $lockKey]);

        return $acquired;
    }

    public function acquireMultipleLocks(array $lockKeys, int $expirationSeconds = self::LOCK_EXPIRATION_SECONDS): bool
    {
        if (!$this->isEnabled) {
            return true;
        }

        $sortedKeys = collect($lockKeys)->unique()->sort()->values()->toArray();

        foreach ($sortedKeys as $lockKey) {
            if (!$this->acquireLock($lockKey, $expirationSeconds)) {
                $this->releaseMultipleLocks($sortedKeys);

                return false;
            }
        }

        return true;
    }

    public function releaseLock(string $lockKey): bool
    {
        if (!$this->isEnabled) {
            return true;
        }

        $fullKey = $this->buildFullKey($lockKey);

        return Cache::lock($fullKey)->release();
    }

    public function releaseMultipleLocks(array $lockKeys): void
    {
        $uniqueKeys = collect($lockKeys)->unique()->toArray();

        foreach ($uniqueKeys as $lockKey) {
            $this->releaseLock($lockKey);
        }
    }

    private function buildFullKey(string $lockKey): string
    {
        $env = config('app.env', 'local');

        return self::LOCK_KEY_PREFIX . ":{$env}:{$lockKey}";
    }
}
