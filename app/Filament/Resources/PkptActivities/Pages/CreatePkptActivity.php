<?php

namespace App\Filament\Resources\PkptActivities\Pages;

use App\Filament\Concerns\HasReliableCancelAction;
use App\Filament\Resources\PkptActivities\PkptActivityResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePkptActivity extends CreateRecord
{
    use HasReliableCancelAction;

    protected static string $resource = PkptActivityResource::class;
}
