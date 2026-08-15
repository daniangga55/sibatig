<?php

namespace App\Filament\Pages;

use App\Filament\Resources\PkptActivities\PkptActivityResource;
use App\Models\PkptActivity;
use App\Models\SptRecord;
use App\Support\SibatigMetrics;
use BackedEnum;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

class Pengumuman extends Page
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedSpeakerWave;

    protected static ?string $navigationLabel = 'Pengumuman';

    protected static string|UnitEnum|null $navigationGroup = 'Lainnya';

    protected static ?int $navigationSort = 20;

    protected static ?string $title = 'Pengumuman';

    protected ?string $subheading = 'Informasi dan pengingat otomatis berdasarkan kondisi PKPT serta SPT terbaru.';

    protected string $view = 'filament.pages.operational-overview';

    public static function getNavigationBadge(): ?string
    {
        $count = rescue(fn (): int => SibatigMetrics::get('spt_in_progress'), 0, report: false);

        return $count > 0 ? (string) $count : null;
    }

    public static function getNavigationBadgeColor(): string|array|null
    {
        return 'warning';
    }

    protected function getViewData(): array
    {
        $activities = PkptActivity::query()
            ->where('year', 2026)
            ->where('progress', '<', 100)
            ->orderByDesc('progress')
            ->take(12)
            ->get();
        $activeSpt = SptRecord::query()->where('year', 2026)->where('status', 'ON PROGRES')->count();

        return [
            'eyebrow' => 'PUSAT INFORMASI',
            'stats' => [
                ['label' => 'SPT berjalan', 'value' => $activeSpt, 'tone' => 'amber'],
                ['label' => 'PKPT belum selesai', 'value' => PkptActivity::query()->where('year', 2026)->where('progress', '<', 100)->count(), 'tone' => 'violet'],
                ['label' => 'PKPT selesai', 'value' => PkptActivity::query()->where('year', 2026)->where('progress', 100)->count(), 'tone' => 'green'],
            ],
            'listTitle' => 'Perlu perhatian',
            'listDescription' => 'Dibuat otomatis dari progres kegiatan terkini.',
            'items' => $activities->map(fn (PkptActivity $activity): array => [
                'date' => 'PKPT No. '.$activity->source_number,
                'title' => $activity->assignment,
                'meta' => ($activity->audit_object ?: 'Inspektorat Kota Kediri').' · Progres '.$activity->progress.'%',
                'status' => $activity->status->label(),
                'tone' => $activity->progress > 0 ? 'amber' : 'gray',
                'url' => PkptActivityResource::getUrl('view', ['record' => $activity]),
            ]),
            'emptyMessage' => 'Tidak ada pengumuman atau kegiatan yang memerlukan perhatian.',
        ];
    }
}
