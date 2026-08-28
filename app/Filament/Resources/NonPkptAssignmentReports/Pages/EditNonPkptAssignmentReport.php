<?php

namespace App\Filament\Resources\NonPkptAssignmentReports\Pages;

use App\Filament\Concerns\HasReliableCancelAction;
use App\Filament\Resources\NonPkptAssignmentReports\NonPkptAssignmentReportResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditNonPkptAssignmentReport extends EditRecord
{
    use HasReliableCancelAction;

    protected static string $resource = NonPkptAssignmentReportResource::class;

    protected function getHeaderActions(): array
    {
        return [ViewAction::make(), DeleteAction::make(), ForceDeleteAction::make(), RestoreAction::make()];
    }
}
