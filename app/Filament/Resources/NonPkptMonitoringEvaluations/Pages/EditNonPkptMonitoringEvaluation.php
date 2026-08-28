<?php

namespace App\Filament\Resources\NonPkptMonitoringEvaluations\Pages;

use App\Filament\Concerns\HasReliableCancelAction;
use App\Filament\Resources\NonPkptMonitoringEvaluations\NonPkptMonitoringEvaluationResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditNonPkptMonitoringEvaluation extends EditRecord
{
    use HasReliableCancelAction;

    protected static string $resource = NonPkptMonitoringEvaluationResource::class;

    protected function getHeaderActions(): array
    {
        return [ViewAction::make(), DeleteAction::make(), ForceDeleteAction::make(), RestoreAction::make()];
    }
}
