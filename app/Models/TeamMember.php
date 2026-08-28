<?php

namespace App\Models;

use App\Models\Concerns\FlushesSibatigMetrics;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable(['user_id', 'full_name', 'nip', 'rank', 'grade', 'position', 'email', 'phone', 'is_leader', 'is_active', 'sort_order', 'notes'])]
class TeamMember extends Model
{
    use FlushesSibatigMetrics, HasFactory, SoftDeletes;

    protected function casts(): array
    {
        return [
            'is_leader' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function pkptActivities(): BelongsToMany
    {
        return $this->belongsToMany(PkptActivity::class)
            ->withPivot('role')
            ->withTimestamps();
    }

    public function nonPkptActivities(): BelongsToMany
    {
        return $this->belongsToMany(NonPkptActivity::class, 'non_pkpt_activity_team_member')
            ->withPivot('role')
            ->withTimestamps();
    }
}
