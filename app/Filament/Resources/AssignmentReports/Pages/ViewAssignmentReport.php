<?php

namespace App\Filament\Resources\AssignmentReports\Pages;

use App\Filament\Resources\AssignmentReports\AssignmentReportResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewAssignmentReport extends ViewRecord
{
    protected static string $resource = AssignmentReportResource::class;

    protected function getHeaderActions(): array
    {
        return [EditAction::make()];
    }
}
