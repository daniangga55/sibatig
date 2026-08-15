<?php

namespace App\Filament\Widgets;

use App\Enums\PkptStatus;
use App\Filament\Resources\MonitoringEvaluations\MonitoringEvaluationResource;
use App\Models\MonitoringEvaluation;
use Filament\Actions\Action;
use Filament\Actions\ViewAction;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class RecentMonitoring extends TableWidget
{
    protected static ?int $sort = 3;

    protected int|string|array $columnSpan = ['md' => 1, 'xl' => 1];

    public function table(Table $table): Table
    {
        return $table
            ->heading('Pembaruan Monitoring Terbaru')
            ->description('Riwayat evaluasi kegiatan PKPT 2026.')
            ->query(fn (): Builder => MonitoringEvaluation::query()->with('pkptActivity')->latest('evaluation_date')->latest('id'))
            ->columns([
                TextColumn::make('pkptActivity.source_number')->label('PKPT')->badge()->color('gray'),
                TextColumn::make('pkptActivity.assignment')->label('Kegiatan')->limit(48)->wrap(),
                TextColumn::make('status')->label('Status')->badge()->formatStateUsing(fn (PkptStatus $state): string => $state->label())->color(fn (PkptStatus $state): string => $state->color()),
                TextColumn::make('progress')->label('Progres')->suffix('%')->alignEnd(),
                TextColumn::make('evaluation_date')->label('Tanggal')->date('d M Y'),
            ])
            ->headerActions([
                Action::make('all')->label('Lihat semua')->icon(Heroicon::OutlinedArrowTopRightOnSquare)->url(MonitoringEvaluationResource::getUrl('index')),
            ])
            ->recordActions([
                ViewAction::make()->url(fn (MonitoringEvaluation $record): string => MonitoringEvaluationResource::getUrl('view', ['record' => $record])),
            ])
            ->paginated([5]);
    }
}
