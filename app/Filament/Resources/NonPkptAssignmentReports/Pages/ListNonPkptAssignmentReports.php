<?php

namespace App\Filament\Resources\NonPkptAssignmentReports\Pages;

use App\Filament\Resources\NonPkptAssignmentReports\NonPkptAssignmentReportResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListNonPkptAssignmentReports extends ListRecords
{
    protected static string $resource = NonPkptAssignmentReportResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
