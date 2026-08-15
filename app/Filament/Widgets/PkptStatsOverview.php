<?php

namespace App\Filament\Widgets;

use App\Enums\PkptStatus;
use App\Filament\Resources\PkptActivities\PkptActivityResource;
use App\Filament\Resources\TeamMembers\TeamMemberResource;
use App\Models\PkptActivity;
use App\Models\TeamMember;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class PkptStatsOverview extends StatsOverviewWidget
{
    protected static ?int $sort = 1;

    protected int|string|array $columnSpan = 'full';

    protected function getStats(): array
    {
        $query = PkptActivity::query()->where('year', 2026);
        $total = (clone $query)->count();
        $started = (clone $query)->where('progress', '>', 0)->count();
        $completed = (clone $query)->where('status', PkptStatus::Selesai)->count();
        $average = round((float) (clone $query)->avg('progress'));

        return [
            Stat::make('Total PKPT 2026', $total)
                ->description('Seluruh kegiatan Irban 3')
                ->descriptionIcon(Heroicon::OutlinedClipboardDocumentCheck)
                ->color('primary')
                ->url(PkptActivityResource::getUrl('index')),
            Stat::make('Sudah berjalan', $started)
                ->description($total > 0 ? round(($started / $total) * 100).'% cakupan realisasi' : 'Belum ada data')
                ->descriptionIcon(Heroicon::OutlinedArrowTrendingUp)
                ->color('info')
                ->url(PkptActivityResource::getUrl('index', ['tableFilters' => ['status' => ['value' => PkptStatus::Berjalan->value]]])),
            Stat::make('Selesai', $completed)
                ->description($total > 0 ? round(($completed / $total) * 100).'% dari total PKPT' : 'Belum ada data')
                ->descriptionIcon(Heroicon::OutlinedCheckCircle)
                ->color('success'),
            Stat::make('Rata-rata progres', $average.'%')
                ->description(TeamMember::query()->where('is_active', true)->count().' anggota tim aktif')
                ->descriptionIcon(Heroicon::OutlinedUserGroup)
                ->color('warning')
                ->url(TeamMemberResource::getUrl('index')),
        ];
    }
}
