<?php

namespace App\Models;

use App\Models\Concerns\HasPrivateAssignmentFile;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable(['year', 'spt_record_id', 'title', 'report_number', 'report_date', 'description', 'storage_disk', 'file_path', 'original_name', 'mime_type', 'file_size', 'uploaded_by'])]
class AssignmentReport extends Model
{
    use HasPrivateAssignmentFile, SoftDeletes;

    protected function casts(): array
    {
        return [
            'year' => 'integer',
            'report_date' => 'date',
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
