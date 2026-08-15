<?php

namespace App\Filament\Resources\MonitoringEvaluations\Pages;

use App\Filament\Concerns\HasReliableCancelAction;
use App\Filament\Resources\MonitoringEvaluations\MonitoringEvaluationResource;
use Filament\Resources\Pages\CreateRecord;

class CreateMonitoringEvaluation extends CreateRecord
{
    use HasReliableCancelAction;

    protected static string $resource = MonitoringEvaluationResource::class;
}
