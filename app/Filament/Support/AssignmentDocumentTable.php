<?php

namespace App\Filament\Support;

use App\Models\AssignmentReport;
use App\Models\WorkPaper;
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

class AssignmentDocumentTable
{
    public static function workPaper(Table $table, string $scope): Table
    {
        return self::configure($table, $scope, false);
    }

    public static function assignmentReport(Table $table, string $scope): Table
    {
        return self::configure($table, $scope, true);
    }

    private static function configure(Table $table, string $scope, bool $report): Table
    {
        $parentRelationship = $scope === 'PKPT' ? 'sptRecord.pkptActivity.assignment' : 'sptRecord.nonPkptActivity.assignment';
        $routeName = $report ? 'assignment-reports.download' : 'work-papers.download';

        return $table
            ->deferLoading()
            ->paginated([10, 25, 50])
            ->defaultPaginationPageOption(10)
            ->columns([
                TextColumn::make('title')->label('Judul dokumen')->searchable()->wrap()->limit(60)->weight('medium'),
                TextColumn::make('sptRecord.assignment_type')->label('Jenis Penugasan')->badge()->sortable(),
                TextColumn::make('sptRecord.document_number')->label('Nomor SPT')->searchable()->copyable(),
                TextColumn::make($parentRelationship)->label($scope === 'PKPT' ? 'Data PKPT' : 'Data Non-PKPT')->wrap()->limit(55),
                TextColumn::make($report ? 'report_number' : 'document_date')
                    ->label($report ? 'Nomor Laporan' : 'Tanggal')
                    ->when($report, fn (TextColumn $column) => $column->searchable()->placeholder('—'), fn (TextColumn $column) => $column->date('d M Y')->sortable()->placeholder('—')),
                TextColumn::make('original_name')->label('File')->limit(30),
                TextColumn::make('uploader.name')->label('Pengunggah')->placeholder('Sistem')->toggleable(),
                TextColumn::make('created_at')->label('Diunggah')->dateTime('d M Y H:i')->sortable()->toggleable(),
            ])
            ->filters([
                TrashedFilter::make(),
                SelectFilter::make('year')->label('Tahun')->options([2026 => '2026'])->default(2026),
                SelectFilter::make('spt_record_id')
                    ->label('Surat Perintah Tugas')
                    ->relationship('sptRecord', 'document_number', modifyQueryUsing: fn ($query) => $query->where('relation_type', $scope))
                    ->searchable()
                    ->preload(),
            ])
            ->defaultSort('created_at', 'desc')
            ->recordActions([
                Action::make('download')
                    ->label('Unduh')
                    ->icon(Heroicon::OutlinedArrowDownTray)
                    ->color('success')
                    ->url(fn (WorkPaper|AssignmentReport $record): string => route($routeName, $record))
                    ->openUrlInNewTab(),
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([DeleteBulkAction::make(), ForceDeleteBulkAction::make(), RestoreBulkAction::make()]),
            ]);
    }
}
