<?php

namespace App\Filament\Resources\WorkPapers\Pages;

use App\Filament\Resources\WorkPapers\WorkPaperResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewWorkPaper extends ViewRecord
{
    protected static string $resource = WorkPaperResource::class;

    protected function getHeaderActions(): array
    {
        return [EditAction::make()];
    }
}
