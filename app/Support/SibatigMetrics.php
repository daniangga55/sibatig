<?php

namespace App\Support;

use App\Models\Document;
use App\Models\MonitoringEvaluation;
use App\Models\PkptActivity;
use App\Models\SptRecord;
use App\Models\TeamMember;
use App\Models\User;

class SibatigMetrics
{
    private const CACHE_KEY = 'sibatig:metrics:v1';

    /** @var array<string, int>|null */
    private static ?array $runtime = null;

    /** @return array<string, int> */
    public static function all(): array
    {
        if (self::$runtime !== null) {
            return self::$runtime;
        }

        return self::$runtime = cache()->remember(
            self::CACHE_KEY,
            now()->addMinutes(10),
            fn (): array => [
                'pkpt_total' => PkptActivity::query()->where('year', 2026)->count(),
                'pkpt_in_progress' => PkptActivity::query()->where('year', 2026)->whereBetween('progress', [1, 99])->count(),
                'team_active' => TeamMember::query()->where('is_active', true)->count(),
                'monitoring_unique' => MonitoringEvaluation::query()
                    ->whereHas('pkptActivity', fn ($query) => $query->where('year', 2026))
                    ->distinct()
                    ->count('pkpt_activity_id'),
                'spt_total' => SptRecord::query()->where('year', 2026)->count(),
                'spt_in_progress' => SptRecord::query()->where('year', 2026)->where('status', 'ON PROGRES')->count(),
                'user_total' => User::query()->count(),
                'document_total' => Document::query()->count(),
            ],
        );
    }

    public static function get(string $key): int
    {
        return self::all()[$key] ?? 0;
    }

    public static function forget(): void
    {
        self::$runtime = null;
        cache()->forget(self::CACHE_KEY);
    }
}
