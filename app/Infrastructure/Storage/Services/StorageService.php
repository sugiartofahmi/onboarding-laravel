<?php

declare(strict_types=1);

namespace App\Infrastructure\Storage\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class StorageService
{
    private string $disk;

    public function __construct()
    {
        $this->disk = config('filesystems.default');
    }

    public function upload(UploadedFile $file, string $directory, ?string $filename = null): string
    {
        try {
            $filename = $filename ?? $this->generateFilename($file);
            $path = $directory . '/' . $filename;

            // Upload file to storage
            $uploaded = Storage::disk($this->disk)->put(
                $path,
                file_get_contents($file->getRealPath())
            );

            if (!$uploaded) {
                throw new \RuntimeException("Storage put operation returned false");
            }

            return $path;
        } catch (\Exception $e) {
            Log::error('File upload failed', [
                'filename' => $file->getClientOriginalName(),
                'directory' => $directory,
                'disk' => $this->disk,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            throw new \RuntimeException(
                "Failed to upload file '{$file->getClientOriginalName()}': {$e->getMessage()}",
                0,
                $e
            );
        }
    }

    public function delete(?string $path): bool
    {
        if (!$path) {
            return true;
        }

        if (!Storage::disk($this->disk)->exists($path)) {
            return true;
        }

        try {
            $result = Storage::disk($this->disk)->delete($path);

            if (!$result) {
                Log::warning('File deletion returned false', [
                    'path' => $path,
                    'disk' => $this->disk
                ]);
            }

            return $result;
        } catch (\Exception $e) {
            Log::error('File deletion failed', [
                'path' => $path,
                'disk' => $this->disk,
                'error' => $e->getMessage()
            ]);

            return false;
        }
    }

    public function getUrl(?string $path): ?string
    {
        if (!$path) {
            return null;
        }

        return Storage::disk($this->disk)->url($path);
    }

    public function exists(string $path): bool
    {
        return Storage::disk($this->disk)->exists($path);
    }

    private function generateFilename(UploadedFile $file): string
    {
        $originalName = $file->getClientOriginalName();
        $extension = $file->getClientOriginalExtension();
        $nameWithoutExtension = pathinfo($originalName, PATHINFO_FILENAME);

        // Sanitize filename - remove special characters
        $sanitizedName = preg_replace('/[^a-zA-Z0-9_-]/', '_', $nameWithoutExtension);

        // Generate datetime prefix (YmdHis format)
        $datetime = now()->format('YmdHis');

        return "{$datetime}_{$sanitizedName}.{$extension}";
    }

    public function getSize(string $path): int
    {
        return Storage::disk($this->disk)->size($path);
    }

    public function getMimeType(string $path): string
    {
        return Storage::disk($this->disk)->mimeType($path);
    }
}
