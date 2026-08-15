<?php

namespace App\Filament\Resources\MonitoringEvaluations\Pages;

use App\Filament\Resources\MonitoringEvaluations\MonitoringEvaluationResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewMonitoringEvaluation extends ViewRecord
{
    protected static string $resource = MonitoringEvaluationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
