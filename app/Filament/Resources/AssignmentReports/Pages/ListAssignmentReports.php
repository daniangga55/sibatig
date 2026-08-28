<?php

namespace App\Filament\Resources\AssignmentReports\Pages;

use App\Filament\Resources\AssignmentReports\AssignmentReportResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListAssignmentReports extends ListRecords
{
    protected static string $resource = AssignmentReportResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
