<?php

namespace App\Models\Concerns;

use App\Support\AssignmentFileStorage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

trait HasPrivateAssignmentFile
{
    public static function bootHasPrivateAssignmentFile(): void
    {
        static::creating(function (Model $file): void {
            $file->storage_disk ??= AssignmentFileStorage::defaultDisk();
            $file->uploaded_by ??= auth()->id();
        });

        static::saving(function (Model $file): void {
            $file->storage_disk ??= AssignmentFileStorage::defaultDisk();

            if ((! $file->isDirty('file_path') && ! $file->isDirty('storage_disk')) || blank($file->file_path)) {
                return;
            }

            $disk = AssignmentFileStorage::disk($file);

            if (! $disk->exists($file->file_path)) {
                return;
            }

            $file->mime_type = $disk->mimeType($file->file_path) ?: null;
            $file->file_size = $disk->size($file->file_path) ?: null;
        });

        static::updated(function (Model $file): void {
            if (! $file->wasChanged('file_path') && ! $file->wasChanged('storage_disk')) {
                return;
            }

            $previousPath = $file->getRawOriginal('file_path');
            $previousDisk = (string) ($file->getRawOriginal('storage_disk') ?: 'local');

            if (filled($previousPath) && ($previousPath !== $file->file_path || $previousDisk !== $file->storage_disk)) {
                Storage::disk($previousDisk)->delete($previousPath);
            }
        });

        static::forceDeleted(function (Model $file): void {
            if (filled($file->file_path)) {
                AssignmentFileStorage::disk($file)->delete($file->file_path);
            }
        });
    }
}
