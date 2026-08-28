<?php

namespace App\Filament\Resources\NonPkptActivities\Pages;

use App\Filament\Resources\NonPkptActivities\NonPkptActivityResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListNonPkptActivities extends ListRecords
{
    protected static string $resource = NonPkptActivityResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
