<?php

namespace App\Models;

use App\Enums\DocumentCategory;
use App\Models\Concerns\FlushesSibatigMetrics;
use App\Support\DocumentStorage;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

#[Fillable(['year', 'category', 'source', 'storage_disk', 'title', 'document_number', 'document_date', 'description', 'file_path', 'original_name', 'mime_type', 'file_size', 'spt_record_id', 'uploaded_by'])]
class Document extends Model
{
    use FlushesSibatigMetrics, SoftDeletes;

    protected static function booted(): void
    {
        static::saving(function (Document $document): void {
            $document->storage_disk ??= DocumentStorage::defaultDisk();

            if ((! $document->isDirty('file_path') && ! $document->isDirty('storage_disk')) || blank($document->file_path)) {
                return;
            }

            $disk = DocumentStorage::disk($document);

            if (! $disk->exists($document->file_path)) {
                return;
            }

            $document->mime_type = $disk->mimeType($document->file_path) ?: null;
            $document->file_size = $disk->size($document->file_path) ?: null;
        });

        static::updated(function (Document $document): void {
            if (! $document->wasChanged('file_path') && ! $document->wasChanged('storage_disk')) {
                return;
            }

            $previousPath = $document->getRawOriginal('file_path');
            $previousDisk = (string) ($document->getRawOriginal('storage_disk') ?: 'local');

            if (filled($previousPath) && ($previousPath !== $document->file_path || $previousDisk !== $document->storage_disk)) {
                Storage::disk($previousDisk)->delete($previousPath);
            }
        });

        static::forceDeleted(function (Document $document): void {
            if (filled($document->file_path)) {
                DocumentStorage::disk($document)->delete($document->file_path);
            }
        });
    }

    protected function casts(): array
    {
        return [
            'year' => 'integer',
            'category' => DocumentCategory::class,
            'document_date' => 'date',
            'file_size' => 'integer',
        ];
    }

    public function sptRecord(): BelongsTo
    {
        return $this->belongsTo(SptRecord::class);
    }

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}
