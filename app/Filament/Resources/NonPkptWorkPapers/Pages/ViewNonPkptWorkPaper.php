<?php

namespace App\Filament\Resources\NonPkptWorkPapers\Pages;

use App\Filament\Resources\NonPkptWorkPapers\NonPkptWorkPaperResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewNonPkptWorkPaper extends ViewRecord
{
    protected static string $resource = NonPkptWorkPaperResource::class;

    protected function getHeaderActions(): array
    {
        return [EditAction::make()];
    }
}
