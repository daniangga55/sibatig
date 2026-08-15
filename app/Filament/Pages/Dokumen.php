<?php

namespace App\Filament\Pages;

use App\Filament\Resources\SptRecords\SptRecordResource;
use App\Models\SptRecord;
use BackedEnum;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

class Dokumen extends Page
{
    protected static bool $shouldRegisterNavigation = false;

    protected static ?string $slug = 'ringkasan-dokumen-lama';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedFolder;

    protected static ?string $navigationLabel = 'Dokumen';

    protected static string|UnitEnum|null $navigationGroup = 'Menu Utama';

    protected static ?int $navigationSort = 60;

    protected static ?string $title = 'Dokumen Pengawasan';

    protected ?string $subheading = 'Indeks laporan hasil pengawasan yang terhubung dengan SPT Irban Tiga.';

    protected string $view = 'filament.pages.operational-overview';

    protected function getViewData(): array
    {
        $allRecords = SptRecord::query()->where('year', 2026);
        $documents = (clone $allRecords)->whereNotNull('report_number')->latest('report_date')->get();

        return [
            'eyebrow' => 'ARSIP DIGITAL',
            'stats' => [
                ['label' => 'Laporan tersedia', 'value' => $documents->count(), 'tone' => 'blue'],
                ['label' => 'SPT tercatat', 'value' => (clone $allRecords)->count(), 'tone' => 'violet'],
                ['label' => 'Menunggu laporan', 'value' => (clone $allRecords)->whereNull('report_number')->count(), 'tone' => 'amber'],
            ],
            'listTitle' => 'Laporan hasil pengawasan',
            'listDescription' => 'Nomor laporan dan kegiatan asalnya.',
            'items' => $documents->map(fn (SptRecord $record): array => [
                'date' => $record->report_date?->locale('id')->translatedFormat('d M Y') ?? 'Tanpa tanggal',
                'title' => $record->report_number,
                'meta' => $record->subject,
                'status' => $record->assignment_type,
                'tone' => 'blue',
                'url' => SptRecordResource::getUrl('view', ['record' => $record]),
            ]),
            'emptyMessage' => 'Belum ada dokumen laporan yang tercatat.',
        ];
    }
}
