<?php

namespace App\Filament\Resources\TeamMembers\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class TeamMembersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->deferLoading()
            ->paginated([10, 25, 50])
            ->defaultPaginationPageOption(10)
            ->columns([
                TextColumn::make('sort_order')->label('Urut')->sortable(),
                TextColumn::make('full_name')->label('Nama')->searchable()->sortable()->weight('medium')->description(fn ($record): string => $record->position),
                TextColumn::make('nip')->label('NIP')->searchable()->copyable(),
                TextColumn::make('rank')->label('Pangkat')->formatStateUsing(fn ($state, $record): string => trim(($state ?? '—').' '.($record->grade ? "({$record->grade})" : ''))),
                IconColumn::make('is_leader')->label('Pimpinan')->boolean(),
                IconColumn::make('is_active')->label('Aktif')->boolean(),
                TextColumn::make('pkpt_activities_count')->label('PKPT')->counts('pkptActivities')->badge()->color('primary'),
            ])
            ->filters([
                TrashedFilter::make(),
                TernaryFilter::make('is_active')->label('Status anggota')->trueLabel('Aktif')->falseLabel('Nonaktif'),
                TernaryFilter::make('is_leader')->label('Pimpinan'),
            ])
            ->defaultSort('sort_order')
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
