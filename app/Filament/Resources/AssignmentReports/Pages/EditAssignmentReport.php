<?php

namespace App\Filament\Resources\AssignmentReports\Pages;

use App\Filament\Concerns\HasReliableCancelAction;
use App\Filament\Resources\AssignmentReports\AssignmentReportResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditAssignmentReport extends EditRecord
{
    use HasReliableCancelAction;

    protected static string $resource = AssignmentReportResource::class;

    protected function getHeaderActions(): array
    {
        return [ViewAction::make(), DeleteAction::make(), ForceDeleteAction::make(), RestoreAction::make()];
    }
}
