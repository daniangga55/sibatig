<?php

namespace App\Filament\Resources\NonPkptActivities\Pages;

use App\Filament\Concerns\HasReliableCancelAction;
use App\Filament\Resources\NonPkptActivities\NonPkptActivityResource;
use Filament\Resources\Pages\CreateRecord;

class CreateNonPkptActivity extends CreateRecord
{
    use HasReliableCancelAction;

    protected static string $resource = NonPkptActivityResource::class;
}
