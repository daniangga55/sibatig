<?php

namespace App\Filament\Resources\NonPkptMonitoringEvaluations\Pages;

use App\Filament\Concerns\HasReliableCancelAction;
use App\Filament\Resources\NonPkptMonitoringEvaluations\NonPkptMonitoringEvaluationResource;
use Filament\Resources\Pages\CreateRecord;

class CreateNonPkptMonitoringEvaluation extends CreateRecord
{
    use HasReliableCancelAction;

    protected static string $resource = NonPkptMonitoringEvaluationResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['pkpt_activity_id'] = null;

        return $data;
    }
}
