<?php

namespace App\Support;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Support\Facades\Storage;

final class AssignmentFileStorage
{
    public static function defaultDisk(): string
    {
        return (string) config('filesystems.documents', 'local');
    }

    public static function diskName(?Model $file = null): string
    {
        return filled($file?->getAttribute('storage_disk'))
            ? (string) $file->getAttribute('storage_disk')
            : self::defaultDisk();
    }

    public static function disk(?Model $file = null): FilesystemAdapter
    {
        return Storage::disk(self::diskName($file));
    }

    /**
     * @param  string|array<string, string>|null  $storedFileNames
     * @return array{name: string, size: int, type: ?string, url: string}|null
     */
    public static function uploadedFileData(
        ?Model $record,
        string $file,
        string|array|null $storedFileNames,
        string $routeName,
    ): ?array {
        if (! $record || $record->getAttribute('file_path') !== $file) {
            return null;
        }

        $storedName = is_array($storedFileNames)
            ? ($storedFileNames[$file] ?? null)
            : $storedFileNames;

        return [
            'name' => $storedName ?: $record->getAttribute('original_name') ?: basename($file),
            'size' => (int) ($record->getAttribute('file_size') ?? 0),
            'type' => $record->getAttribute('mime_type'),
            'url' => route($routeName, $record),
        ];
    }
}
