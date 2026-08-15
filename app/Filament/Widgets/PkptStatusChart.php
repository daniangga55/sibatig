<?php

namespace App\Filament\Widgets;

use App\Enums\PkptStatus;
use App\Models\PkptActivity;
use Filament\Widgets\ChartWidget;

class PkptStatusChart extends ChartWidget
{
    protected ?string $heading = 'Komposisi Status PKPT 2026';

    protected ?string $description = 'Status terkini disinkronkan dari entri monitoring terbaru.';

    protected static ?int $sort = 2;

    protected int|string|array $columnSpan = ['md' => 1, 'xl' => 1];

    protected function getData(): array
    {
        $statuses = collect(PkptStatus::cases());
        $counts = $statuses->map(fn (PkptStatus $status): int => PkptActivity::query()->where('year', 2026)->where('status', $status)->count());

        return [
            'datasets' => [[
                'label' => 'Kegiatan',
                'data' => $counts->all(),
                'backgroundColor' => ['#94a3b8', '#38bdf8', '#f59e0b', '#8b5cf6', '#10b981', '#ef4444'],
                'borderWidth' => 0,
            ]],
            'labels' => $statuses->map(fn (PkptStatus $status): string => $status->label())->all(),
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
