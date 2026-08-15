<?php

namespace App\Filament\Resources\MonitoringEvaluations\Pages;

use App\Filament\Resources\MonitoringEvaluations\MonitoringEvaluationResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListMonitoringEvaluations extends ListRecords
{
    protected static string $resource = MonitoringEvaluationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
