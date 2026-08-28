<?php

namespace App\Filament\Resources\NonPkptWorkPapers\Pages;

use App\Filament\Concerns\HasReliableCancelAction;
use App\Filament\Resources\NonPkptWorkPapers\NonPkptWorkPaperResource;
use Filament\Resources\Pages\CreateRecord;

class CreateNonPkptWorkPaper extends CreateRecord
{
    use HasReliableCancelAction;

    protected static string $resource = NonPkptWorkPaperResource::class;
}
