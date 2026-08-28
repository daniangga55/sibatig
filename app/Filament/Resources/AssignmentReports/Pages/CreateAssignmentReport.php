<?php

namespace App\Filament\Resources\AssignmentReports\Pages;

use App\Filament\Concerns\HasReliableCancelAction;
use App\Filament\Resources\AssignmentReports\AssignmentReportResource;
use Filament\Resources\Pages\CreateRecord;

class CreateAssignmentReport extends CreateRecord
{
    use HasReliableCancelAction;

    protected static string $resource = AssignmentReportResource::class;
}
