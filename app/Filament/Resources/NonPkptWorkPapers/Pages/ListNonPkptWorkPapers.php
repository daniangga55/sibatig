<?php

namespace App\Filament\Resources\NonPkptWorkPapers\Pages;

use App\Filament\Resources\NonPkptWorkPapers\NonPkptWorkPaperResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListNonPkptWorkPapers extends ListRecords
{
    protected static string $resource = NonPkptWorkPaperResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
