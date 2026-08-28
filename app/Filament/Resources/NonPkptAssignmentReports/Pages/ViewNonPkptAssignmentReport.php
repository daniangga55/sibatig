<?php

namespace App\Filament\Resources\NonPkptAssignmentReports\Pages;

use App\Filament\Resources\NonPkptAssignmentReports\NonPkptAssignmentReportResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewNonPkptAssignmentReport extends ViewRecord
{
    protected static string $resource = NonPkptAssignmentReportResource::class;

    protected function getHeaderActions(): array
    {
        return [EditAction::make()];
    }
}
