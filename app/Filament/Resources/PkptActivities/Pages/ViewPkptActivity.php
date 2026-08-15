<?php

namespace App\Filament\Resources\PkptActivities\Pages;

use App\Filament\Resources\MonitoringEvaluations\MonitoringEvaluationResource;
use App\Filament\Resources\PkptActivities\PkptActivityResource;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;
use Filament\Support\Icons\Heroicon;

class ViewPkptActivity extends ViewRecord
{
    protected static string $resource = PkptActivityResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('addMonitoring')
                ->label('Tambah Monev')
                ->icon(Heroicon::OutlinedPresentationChartLine)
                ->url(fn (): string => MonitoringEvaluationResource::getUrl('create', ['pkpt_activity_id' => $this->getRecord()->getKey()])),
            EditAction::make(),
        ];
    }
}
