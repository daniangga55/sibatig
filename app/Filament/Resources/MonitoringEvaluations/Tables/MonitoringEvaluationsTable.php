<?php

namespace App\Filament\Resources\MonitoringEvaluations\Tables;

use App\Enums\PkptStatus;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class MonitoringEvaluationsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->deferLoading()
            ->paginated([10, 25, 50])
            ->defaultPaginationPageOption(10)
            ->columns([
                TextColumn::make('pkptActivity.source_number')->label('No. PKPT')->badge()->color('gray')->sortable(),
                TextColumn::make('pkptActivity.assignment')->label('Kegiatan')->searchable()->wrap()->limit(70)->weight('medium'),
                TextColumn::make('evaluation_date')->label('Tanggal evaluasi')->date('d M Y')->sortable(),
                TextColumn::make('status')->label('Status')->badge()->formatStateUsing(fn (PkptStatus $state): string => $state->label())->color(fn (PkptStatus $state): string => $state->color()),
                TextColumn::make('progress')->label('Progres')->suffix('%')->sortable()->alignEnd(),
                TextColumn::make('stage')->label('Tahapan')->placeholder('—')->toggleable(),
                TextColumn::make('updater.name')->label('Diperbarui oleh')->placeholder('Sistem')->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TrashedFilter::make(),
                SelectFilter::make('status')->label('Status')->options(PkptStatus::options()),
                SelectFilter::make('pkpt_activity_id')
                    ->label('Kegiatan PKPT')
                    ->relationship('pkptActivity', 'assignment', modifyQueryUsing: fn ($query) => $query->where('year', 2026)->orderBy('source_number'))
                    ->searchable()
                    ->preload(),
            ])
            ->defaultSort('evaluation_date', 'desc')
            ->recordActions([
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
}
