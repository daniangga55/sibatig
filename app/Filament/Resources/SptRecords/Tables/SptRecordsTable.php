<?php

namespace App\Filament\Resources\SptRecords\Tables;

use App\Filament\Pages\KalenderKegiatan;
use App\Models\SptRecord;
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

class SptRecordsTable
{
    public static function configure(Table $table, string $scope = 'PKPT'): Table
    {
        $relationship = $scope === 'PKPT' ? 'pkptActivity' : 'nonPkptActivity';

        return $table
            ->deferLoading()
            ->paginated([10, 25, 50])
            ->defaultPaginationPageOption(10)
            ->columns([
                TextColumn::make('source_number')->label('No.')->sortable()->alignCenter(),
                TextColumn::make('document_number')->label('Nomor SPT')->searchable()->copyable()->weight('medium'),
                TextColumn::make('document_date')->label('Tanggal')->date('d M Y')->sortable(),
                TextColumn::make('subject')->label('Uraian penugasan')->searchable()->wrap()->limit(75),
                TextColumn::make('assignment_type')->label('Jenis Penugasan')->badge()->sortable(),
                TextColumn::make($relationship.'.source_number')->label($scope)->badge()->sortable(),
                TextColumn::make('status')->label('Status')->badge()->color(fn (string $state): string => $state === 'SELESAI' ? 'success' : 'warning'),
                TextColumn::make('documents_count')->label('File SPT')->counts('documents')->badge()->color('info'),
                TextColumn::make('start_date')->label('Pelaksanaan')->date('d M Y')->toggleable(),
                TextColumn::make('end_date')->label('Selesai')->date('d M Y')->placeholder('—')->toggleable(),
            ])
            ->filters([
                TrashedFilter::make(),
                SelectFilter::make('assignment_type')->label('Jenis')->options(['AUDIT' => 'Audit', 'REVIU' => 'Reviu', 'MONITORING' => 'Monitoring', 'EVALUASI' => 'Evaluasi', 'PENDAMPINGAN' => 'Pendampingan', 'MANDATORY' => 'Mandatory']),
                SelectFilter::make('status')->label('Status')->options(['SELESAI' => 'Selesai', 'ON PROGRES' => 'On progress']),
            ])
            ->defaultSort('source_number')
            ->recordActions([
                Action::make('calendar')
                    ->label('Lihat di Kalender')
                    ->icon(Heroicon::OutlinedCalendarDays)
                    ->color('info')
                    ->url(fn (SptRecord $record): string => KalenderKegiatan::getUrl(['tanggal' => $record->start_date->toDateString()]))
                    ->extraAttributes(['wire:navigate' => '']),
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([DeleteBulkAction::make(), ForceDeleteBulkAction::make(), RestoreBulkAction::make()]),
            ]);
    }
}
