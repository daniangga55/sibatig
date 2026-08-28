<?php

namespace App\Models;

use App\Enums\PkptStatus;
use App\Models\Concerns\FlushesSibatigMetrics;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable(['pkpt_activity_id', 'non_pkpt_activity_id', 'evaluation_date', 'status', 'progress', 'stage', 'actual_start', 'actual_end', 'achievement', 'obstacles', 'follow_up', 'updated_by'])]
class MonitoringEvaluation extends Model
{
    use FlushesSibatigMetrics, HasFactory, SoftDeletes;

    protected function casts(): array
    {
        return [
            'evaluation_date' => 'date',
            'status' => PkptStatus::class,
            'progress' => 'integer',
            'actual_start' => 'date',
            'actual_end' => 'date',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (MonitoringEvaluation $evaluation): void {
            $evaluation->updated_by ??= auth()->id();
        });

        static::updating(function (MonitoringEvaluation $evaluation): void {
            $evaluation->updated_by = auth()->id() ?? $evaluation->updated_by;
        });

        static::saved(fn (MonitoringEvaluation $evaluation) => $evaluation->syncActivitySummary());
        static::deleted(fn (MonitoringEvaluation $evaluation) => $evaluation->syncActivitySummary());
        static::restored(fn (MonitoringEvaluation $evaluation) => $evaluation->syncActivitySummary());
    }

    public function pkptActivity(): BelongsTo
    {
        return $this->belongsTo(PkptActivity::class);
    }

    public function nonPkptActivity(): BelongsTo
    {
        return $this->belongsTo(NonPkptActivity::class);
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function syncPkptSummary(): void
    {
        $this->syncActivitySummary();
    }

    public function syncActivitySummary(): void
    {
        $activity = $this->pkpt_activity_id
            ? $this->pkptActivity()->withTrashed()->first()
            : $this->nonPkptActivity()->withTrashed()->first();

        if (! $activity || $activity->trashed()) {
            return;
        }

        $latest = $activity->monitoringEvaluations()
            ->latest('evaluation_date')
            ->latest('id')
            ->first();

        $activity->update([
            'status' => $latest?->status ?? PkptStatus::BelumDilaksanakan,
            'progress' => $latest?->progress ?? 0,
            'actual_start' => $latest?->actual_start,
            'actual_end' => $latest?->actual_end,
        ]);
    }
}
