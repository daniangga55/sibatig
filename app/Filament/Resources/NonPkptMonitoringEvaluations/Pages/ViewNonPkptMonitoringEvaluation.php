<?php

namespace App\Filament\Resources\NonPkptMonitoringEvaluations\Pages;

use App\Filament\Resources\NonPkptMonitoringEvaluations\NonPkptMonitoringEvaluationResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewNonPkptMonitoringEvaluation extends ViewRecord
{
    protected static string $resource = NonPkptMonitoringEvaluationResource::class;

    protected function getHeaderActions(): array
    {
        return [EditAction::make()];
    }
}
