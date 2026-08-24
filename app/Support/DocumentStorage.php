<?php

namespace App\Support;

use App\Models\Document;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Support\Facades\Storage;

final class DocumentStorage
{
    public static function defaultDisk(): string
    {
        return (string) config('filesystems.documents', 'local');
    }

    public static function diskName(?Document $document = null): string
    {
        return filled($document?->storage_disk)
            ? (string) $document->storage_disk
            : self::defaultDisk();
    }

    public static function disk(?Document $document = null): FilesystemAdapter
    {
        return Storage::disk(self::diskName($document));
    }

    /**
     * Metadata FileUpload menggunakan route aplikasi, bukan URL publik dari disk.
     * Dengan demikian disk lokal privat dan Google Drive memiliki perilaku UI sama.
     *
     * @param  string|array<string, string>|null  $storedFileNames
     * @return array{name: string, size: int, type: ?string, url: string}|null
     */
    public static function uploadedFileData(
        ?Document $document,
        string $file,
        string|array|null $storedFileNames = null,
    ): ?array {
        if (! $document || $document->file_path !== $file) {
            return null;
        }

        $storedName = is_array($storedFileNames)
            ? ($storedFileNames[$file] ?? null)
            : $storedFileNames;

        return [
            'name' => $storedName ?: $document->original_name ?: basename($file),
            'size' => (int) ($document->file_size ?? 0),
            'type' => $document->mime_type,
            'url' => route('documents.download', $document),
        ];
    }
}
