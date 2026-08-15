<?php

namespace App\Filament\Resources\PkptActivities\Pages;

use App\Filament\Resources\PkptActivities\PkptActivityResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPkptActivities extends ListRecords
{
    protected static string $resource = PkptActivityResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
