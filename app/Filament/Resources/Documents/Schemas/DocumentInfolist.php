<?php

namespace App\Filament\Resources\Documents\Schemas;

use App\Enums\DocumentCategory;
use App\Models\Document;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class DocumentInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Informasi dokumen')
                ->columns(3)
                ->schema([
                    TextEntry::make('category')
                        ->label('Kategori')
                        ->badge()
                        ->formatStateUsing(fn (DocumentCategory $state): string => $state->label())
                        ->color(fn (DocumentCategory $state): string => $state->color()),
                    TextEntry::make('year')->label('Tahun'),
                    TextEntry::make('document_date')->label('Tanggal')->date('d M Y')->placeholder('—'),
                    TextEntry::make('title')->label('Judul')->columnSpan(2),
                    TextEntry::make('document_number')->label('Nomor')->placeholder('—'),
                    TextEntry::make('sptRecord.document_number')->label('Rekap SPT terkait')->placeholder('Tidak terkait SPT')->columnSpan(2),
                    TextEntry::make('uploader.name')->label('Diunggah oleh')->placeholder('Sistem'),
                    TextEntry::make('description')->label('Keterangan')->placeholder('—')->columnSpanFull(),
                ]),
            Section::make('File tersimpan')
                ->columns(3)
                ->schema([
                    TextEntry::make('original_name')
                        ->label('Nama file')
                        ->url(fn (Document $record): string => route('documents.download', $record))
                        ->openUrlInNewTab()
                        ->color('primary'),
                    TextEntry::make('mime_type')->label('Tipe file')->placeholder('—'),
                    TextEntry::make('file_size')->label('Ukuran')->formatStateUsing(fn (?int $state): string => self::formatBytes($state)),
                    TextEntry::make('created_at')->label('Waktu unggah')->dateTime('d M Y H:i'),
                    TextEntry::make('updated_at')->label('Terakhir diperbarui')->dateTime('d M Y H:i'),
                ]),
        ]);
    }

    private static function formatBytes(?int $bytes): string
    {
        if (! $bytes) {
            return '—';
        }

        return $bytes >= 1048576
            ? number_format($bytes / 1048576, 2, ',', '.').' MB'
            : number_format($bytes / 1024, 1, ',', '.').' KB';
    }
}
