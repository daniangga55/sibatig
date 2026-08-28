<?php

namespace App\Filament\Resources\WorkPapers\Pages;

use App\Filament\Concerns\HasReliableCancelAction;
use App\Filament\Resources\WorkPapers\WorkPaperResource;
use Filament\Resources\Pages\CreateRecord;

class CreateWorkPaper extends CreateRecord
{
    use HasReliableCancelAction;

    protected static string $resource = WorkPaperResource::class;
}
