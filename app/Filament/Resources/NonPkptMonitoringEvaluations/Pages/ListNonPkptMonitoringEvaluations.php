<?php

namespace App\Filament\Resources\NonPkptMonitoringEvaluations\Pages;

use App\Filament\Resources\NonPkptMonitoringEvaluations\NonPkptMonitoringEvaluationResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListNonPkptMonitoringEvaluations extends ListRecords
{
    protected static string $resource = NonPkptMonitoringEvaluationResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
