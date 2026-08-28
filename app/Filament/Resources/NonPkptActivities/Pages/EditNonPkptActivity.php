<?php

namespace App\Filament\Resources\NonPkptActivities\Pages;

use App\Filament\Concerns\HasReliableCancelAction;
use App\Filament\Resources\NonPkptActivities\NonPkptActivityResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditNonPkptActivity extends EditRecord
{
    use HasReliableCancelAction;

    protected static string $resource = NonPkptActivityResource::class;

    protected function getHeaderActions(): array
    {
        return [ViewAction::make(), DeleteAction::make(), ForceDeleteAction::make(), RestoreAction::make()];
    }
}
