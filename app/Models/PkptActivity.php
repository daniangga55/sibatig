<?php

namespace App\Models;

use App\Enums\PkptCategory;
use App\Enums\PkptStatus;
use App\Models\Concerns\FlushesSibatigMetrics;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable(['year', 'source_number', 'category', 'assignment_type', 'assignment', 'audit_object', 'executor', 'apip_count', 'status', 'progress', 'planned_start', 'planned_end', 'actual_start', 'actual_end', 'notes', 'created_by', 'updated_by'])]
class PkptActivity extends Model
{
    use FlushesSibatigMetrics, HasFactory, SoftDeletes;

    protected function casts(): array
    {
        return [
            'category' => PkptCategory::class,
            'status' => PkptStatus::class,
            'planned_start' => 'date',
            'planned_end' => 'date',
            'actual_start' => 'date',
            'actual_end' => 'date',
            'progress' => 'integer',
            'apip_count' => 'integer',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (PkptActivity $activity): void {
            $activity->created_by ??= auth()->id();
            $activity->updated_by ??= auth()->id();
        });

        static::updating(function (PkptActivity $activity): void {
            $activity->updated_by = auth()->id() ?? $activity->updated_by;
        });
    }

    public function teamMembers(): BelongsToMany
    {
        return $this->belongsToMany(TeamMember::class)
            ->withPivot('role')
            ->withTimestamps();
    }

    public function monitoringEvaluations(): HasMany
    {
        return $this->hasMany(MonitoringEvaluation::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
