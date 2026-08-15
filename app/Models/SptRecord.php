<?php

namespace App\Models;

use App\Models\Concerns\FlushesSibatigMetrics;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable(['year', 'source_number', 'document_number', 'document_date', 'start_date', 'end_date', 'report_due_date', 'subject', 'audit_object', 'report_number', 'report_date', 'assignment_type', 'relation_type', 'status', 'pkpt_activity_id', 'match_type', 'notes'])]
class SptRecord extends Model
{
    use FlushesSibatigMetrics, HasFactory, SoftDeletes;

    protected function casts(): array
    {
        return [
            'year' => 'integer',
            'source_number' => 'integer',
            'document_date' => 'date',
            'start_date' => 'date',
            'end_date' => 'date',
            'report_due_date' => 'date',
            'report_date' => 'date',
        ];
    }

    public function pkptActivity(): BelongsTo
    {
        return $this->belongsTo(PkptActivity::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(Document::class);
    }
}
