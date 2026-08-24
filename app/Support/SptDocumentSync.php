<?php

namespace App\Support;

use App\Enums\DocumentCategory;
use App\Models\Document;
use App\Models\SptRecord;
use Illuminate\Support\Arr;

final class SptDocumentSync
{
    public const SOURCE = 'REKAP_SPT';

    public static function documentFor(SptRecord $record, bool $withTrashed = false): ?Document
    {
        $query = $record->documents()->where('source', self::SOURCE);

        if ($withTrashed) {
            $query->withTrashed();
        }

        return $query->first();
    }

    public static function pathFromState(mixed $state): ?string
    {
        $path = Arr::last(Arr::wrap($state));

        return is_string($path) && filled($path) ? $path : null;
    }

    public static function diskNameFor(?SptRecord $record): string
    {
        if (! $record) {
            return DocumentStorage::defaultDisk();
        }

        return DocumentStorage::diskName(self::documentFor($record, withTrashed: true));
    }

    public static function sync(SptRecord $record, ?string $path, ?string $originalName, ?int $uploaderId): void
    {
        $document = self::documentFor($record, withTrashed: true);

        if (blank($path)) {
            if ($document && ! $document->trashed()) {
                $document->delete();
            }

            return;
        }

        $attributes = [
            'year' => $record->year,
            'category' => DocumentCategory::Spt,
            'source' => self::SOURCE,
            'storage_disk' => self::diskNameFor($record),
            'title' => "File SPT {$record->document_number}",
            'document_number' => $record->document_number,
            'document_date' => $record->document_date,
            'description' => $record->subject,
            'file_path' => $path,
            'original_name' => $originalName ?: basename($path),
            'spt_record_id' => $record->getKey(),
            'uploaded_by' => $uploaderId,
        ];

        if ($document) {
            if ($document->trashed()) {
                $document->restore();
            }

            $document->update($attributes);

            return;
        }

        Document::query()->create($attributes);
    }
}
