<?php

namespace App\Filament\Resources\NonPkptAssignmentReports\Pages;

use App\Filament\Concerns\HasReliableCancelAction;
use App\Filament\Resources\NonPkptAssignmentReports\NonPkptAssignmentReportResource;
use Filament\Resources\Pages\CreateRecord;

class CreateNonPkptAssignmentReport extends CreateRecord
{
    use HasReliableCancelAction;

    protected static string $resource = NonPkptAssignmentReportResource::class;
}
