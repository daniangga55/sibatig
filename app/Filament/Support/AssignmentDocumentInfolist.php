<?php

namespace App\Filament\Support;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class AssignmentDocumentInfolist
{
    public static function workPaper(Schema $schema): Schema
    {
        return self::configure($schema, false);
    }

    public static function assignmentReport(Schema $schema): Schema
    {
        return self::configure($schema, true);
    }

    private static function configure(Schema $schema, bool $report): Schema
    {
        return $schema->components([
            Section::make($report ? 'Laporan Hasil Penugasan' : 'Kertas Kerja')
                ->columns(3)
                ->schema([
                    TextEntry::make('title')->label('Judul')->columnSpan(2),
                    TextEntry::make('year')->label('Tahun'),
                    TextEntry::make('sptRecord.document_number')->label('Nomor SPT')->copyable(),
                    TextEntry::make('sptRecord.assignment_type')->label('Jenis Penugasan')->badge(),
                    TextEntry::make('sptRecord.relation_type')->label('Kelompok')->badge(),
                    ...($report ? [
                        TextEntry::make('report_number')->label('Nomor laporan')->placeholder('—')->copyable(),
                        TextEntry::make('report_date')->label('Tanggal laporan')->date('d M Y')->placeholder('—'),
                    ] : [
                        TextEntry::make('document_date')->label('Tanggal dokumen')->date('d M Y')->placeholder('—'),
                    ]),
                    TextEntry::make('original_name')->label('Nama file')->columnSpan(2),
                    TextEntry::make('description')->label('Keterangan')->placeholder('—')->columnSpanFull(),
                ]),
        ]);
    }
}
