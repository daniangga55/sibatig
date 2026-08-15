<?php

namespace App\Models;

use App\Enums\DocumentCategory;
use App\Models\Concerns\FlushesSibatigMetrics;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

#[Fillable(['year', 'category', 'source', 'title', 'document_number', 'document_date', 'description', 'file_path', 'original_name', 'mime_type', 'file_size', 'spt_record_id', 'uploaded_by'])]
class Document extends Model
{
    use FlushesSibatigMetrics, SoftDeletes;

    protected static function booted(): void
    {
        static::saving(function (Document $document): void {
            if (! $document->isDirty('file_path') || blank($document->file_path)) {
                return;
            }

            $disk = Storage::disk('local');

            if (! $disk->exists($document->file_path)) {
                return;
            }

            $document->mime_type = $disk->mimeType($document->file_path) ?: null;
            $document->file_size = $disk->size($document->file_path) ?: null;
        });

        static::updated(function (Document $document): void {
            if (! $document->wasChanged('file_path')) {
                return;
            }

            $previousPath = $document->getRawOriginal('file_path');

            if (filled($previousPath) && $previousPath !== $document->file_path) {
                Storage::disk('local')->delete($previousPath);
            }
        });

        static::forceDeleted(function (Document $document): void {
            if (filled($document->file_path)) {
                Storage::disk('local')->delete($document->file_path);
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
