<?php

namespace App\Filament\Resources\NonPkptActivities\Pages;

use App\Filament\Resources\NonPkptActivities\NonPkptActivityResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewNonPkptActivity extends ViewRecord
{
    protected static string $resource = NonPkptActivityResource::class;

    protected function getHeaderActions(): array
    {
        return [EditAction::make()];
    }
}
