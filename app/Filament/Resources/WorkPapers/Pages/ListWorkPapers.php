<?php

namespace App\Filament\Resources\WorkPapers\Pages;

use App\Filament\Resources\WorkPapers\WorkPaperResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListWorkPapers extends ListRecords
{
    protected static string $resource = WorkPaperResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
