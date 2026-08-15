<?php

namespace App\Filament\Pages;

use App\Enums\PkptStatus;
use App\Filament\Resources\MonitoringEvaluations\MonitoringEvaluationResource;
use App\Filament\Resources\PkptActivities\PkptActivityResource;
use App\Models\PkptActivity;
use App\Models\WebsiteSetting;
use Carbon\Carbon;
use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Schemas\Components\View;
use Filament\Schemas\Schema;
use UnitEnum;

class Dashboard extends BaseDashboard
{
    protected static ?string $navigationLabel = 'Dashboard';

    protected static string|UnitEnum|null $navigationGroup = 'Menu Utama';

    protected static ?int $navigationSort = 10;

    public function getHeading(): string
    {
        return '';
    }

    public function getSubheading(): ?string
    {
        return null;
    }

    public function getColumns(): int|array
    {
        return ['md' => 2, 'xl' => 2];
    }

    public function content(Schema $schema): Schema
    {
        $year = WebsiteSetting::current()?->active_year ?? 2026;
        $activities = PkptActivity::query()
            ->where('year', $year)
            ->with(['monitoringEvaluations' => fn ($query) => $query
                ->latest('evaluation_date')
                ->latest('id')])
            ->get();

        $total = $activities->count();
        $withMonitoring = $activities->filter(fn (PkptActivity $activity): bool => $activity->monitoringEvaluations->isNotEmpty());
        $started = $withMonitoring->count();
        $completed = $activities->where('status', PkptStatus::Selesai)->count();
        $coverage = $total > 0 ? (int) round(($started / $total) * 100) : 0;
        $latestMonitoring = $withMonitoring
            ->sortByDesc(fn (PkptActivity $activity): string => $activity->monitoringEvaluations->first()?->evaluation_date?->format('Y-m-d') ?? '')
            ->take(6)
            ->values();
        $categoryCounts = $activities
            ->countBy(fn (PkptActivity $activity): string => $activity->category->value)
            ->all();

        $visibleMonths = $year === (int) now()->format('Y') ? (int) now()->format('n') : 12;
        $monthlyCoverage = collect(range(1, $visibleMonths))->map(function (int $month) use ($activities, $total, $year): array {
            $monthEnd = Carbon::create($year, $month)->endOfMonth();
            $realized = $activities->filter(fn (PkptActivity $activity): bool => $activity->monitoringEvaluations
                ->contains(fn ($evaluation): bool => $evaluation->evaluation_date->lte($monthEnd)))
                ->count();

            return [
                'label' => Carbon::create($year, $month)->locale('id')->translatedFormat('M'),
                'percentage' => $total > 0 ? (int) round(($realized / $total) * 100) : 0,
            ];
        });

        return $schema->components([
            View::make('filament.pages.dashboard-hero')
                ->viewData([
                    'year' => $year,
                    'total' => $total,
                    'started' => $started,
                    'completed' => $completed,
                    'coverage' => $coverage,
                    'activitiesWithoutMonitoring' => $total - $started,
                    'categoryCounts' => $categoryCounts,
                    'latestMonitoring' => $latestMonitoring,
                    'monthlyCoverage' => $monthlyCoverage,
                    'pkptUrl' => PkptActivityResource::getUrl('index'),
                    'monitoringUrl' => MonitoringEvaluationResource::getUrl('index'),
                ])
                ->columnSpanFull(),
        ]);
    }
}
