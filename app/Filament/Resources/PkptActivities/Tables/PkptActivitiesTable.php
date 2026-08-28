<?php

namespace App\Filament\Resources\PkptActivities\Tables;

use App\Enums\PkptCategory;
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
use Illuminate\Support\Str;

class PkptActivitiesTable
{
    public static function configure(
        Table $table,
        string $monitoringRelation = 'monitoringEvaluations',
        string $scopeLabel = 'PKPT',
    ): Table {
        return $table
            ->deferLoading()
            ->paginated([10, 25, 50])
            ->defaultPaginationPageOption(10)
            ->columns([
                TextColumn::make('source_number')->label('No.')->badge()->color('gray')->sortable(),
                TextColumn::make('category')->label('Kategori')->badge()->formatStateUsing(fn (PkptCategory $state): string => $state->label())->searchable(),
                TextColumn::make('assignment')->label('Penugasan')->searchable()->wrap()->limit(80)->tooltip(fn ($record): string => $record->assignment)->weight('medium'),
                TextColumn::make('audit_object')->label('Obrik')->searchable()->wrap()->limit(45)->placeholder('—')->toggleable(),
                TextColumn::make('status')
                    ->label('Status')->badge()
                    ->formatStateUsing(fn (PkptStatus $state): string => $state->label())
                    ->color(fn (PkptStatus $state): string => $state->color()),
                TextColumn::make('progress')->label('Progres')->suffix('%')->sortable()->alignEnd(),
                TextColumn::make('executor')->label('Pelaksana')->badge()->color('info')->toggleable(),
                TextColumn::make(Str::snake($monitoringRelation).'_count')->label('Monev')->counts($monitoringRelation)->badge()->color('primary'),
                TextColumn::make('year')->label('Tahun')->sortable()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TrashedFilter::make(),
                SelectFilter::make('year')->label('Tahun')->options([2026 => '2026'])->default(2026),
                SelectFilter::make('category')->label('Kategori')->options(PkptCategory::options()),
                SelectFilter::make('status')->label('Status')->options(PkptStatus::options()),
            ])
            ->defaultSort('source_number')
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
