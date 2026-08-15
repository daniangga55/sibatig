<?php

namespace App\Filament\Resources\Documents\Tables;

use App\Enums\DocumentCategory;
use App\Models\Document;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class DocumentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->deferLoading()
            ->paginated([10, 25, 50])
            ->defaultPaginationPageOption(10)
            ->columns([
                TextColumn::make('category')
                    ->label('Kategori')
                    ->badge()
                    ->formatStateUsing(fn (DocumentCategory $state): string => $state->label())
                    ->color(fn (DocumentCategory $state): string => $state->color())
                    ->sortable(),
                TextColumn::make('title')->label('Judul dokumen')->searchable()->wrap()->limit(65)->weight('medium'),
                TextColumn::make('document_number')->label('Nomor')->searchable()->placeholder('—')->toggleable(),
                TextColumn::make('sptRecord.document_number')->label('Rekap SPT')->badge()->placeholder('—')->toggleable(),
                TextColumn::make('document_date')->label('Tanggal')->date('d M Y')->sortable()->placeholder('—'),
                TextColumn::make('original_name')->label('File')->searchable()->limit(35)->tooltip(fn (Document $record): string => $record->original_name),
                TextColumn::make('file_size')->label('Ukuran')->formatStateUsing(fn (?int $state): string => self::formatBytes($state))->alignEnd(),
                TextColumn::make('uploader.name')->label('Pengunggah')->placeholder('Sistem')->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')->label('Diunggah')->dateTime('d M Y H:i')->sortable()->toggleable(),
            ])
            ->filters([
                TrashedFilter::make(),
                SelectFilter::make('category')->label('Kategori')->options(DocumentCategory::options()),
                SelectFilter::make('year')->label('Tahun')->options([2026 => '2026'])->default(2026),
                SelectFilter::make('spt_record_id')
                    ->label('Rekap SPT')
                    ->relationship('sptRecord', 'document_number')
                    ->searchable()
                    ->preload(),
            ])
            ->defaultSort('created_at', 'desc')
            ->recordActions([
                Action::make('download')
                    ->label('Unduh')
                    ->icon(Heroicon::OutlinedArrowDownTray)
                    ->color('success')
                    ->url(fn (Document $record): string => route('documents.download', $record))
                    ->openUrlInNewTab(),
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
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
