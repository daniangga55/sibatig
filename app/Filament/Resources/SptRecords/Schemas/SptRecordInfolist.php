<?php

namespace App\Filament\Resources\SptRecords\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class SptRecordInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Ringkasan SPT')->columns(3)->schema([
                TextEntry::make('document_number')->label('Nomor SPT')->copyable(),
                TextEntry::make('document_date')->label('Tanggal SPT')->date('d M Y'),
                TextEntry::make('status')->label('Status')->badge()->color(fn (string $state): string => $state === 'SELESAI' ? 'success' : 'warning'),
                TextEntry::make('subject')->label('Uraian penugasan')->columnSpanFull(),
                TextEntry::make('audit_object')->label('Objek pemeriksaan')->placeholder('—')->columnSpanFull(),
            ]),
            Section::make('Pelaksanaan dan integrasi')->columns(3)->schema([
                TextEntry::make('start_date')->label('Mulai')->date('d M Y'),
                TextEntry::make('end_date')->label('Selesai')->date('d M Y')->placeholder('—'),
                TextEntry::make('assignment_type')->label('Jenis')->badge(),
                TextEntry::make('pkptActivity.source_number')->label('No. PKPT')->badge()->placeholder('Non-PKPT'),
                TextEntry::make('report_number')->label('Nomor laporan')->placeholder('—')->copyable(),
                TextEntry::make('report_date')->label('Tanggal laporan')->date('d M Y')->placeholder('—'),
            ]),
        ]);
    }
}
