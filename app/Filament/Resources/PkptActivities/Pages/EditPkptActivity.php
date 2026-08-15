<?php

namespace App\Filament\Resources\PkptActivities\Pages;

use App\Filament\Concerns\HasReliableCancelAction;
use App\Filament\Resources\PkptActivities\PkptActivityResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditPkptActivity extends EditRecord
{
    use HasReliableCancelAction;

    protected static string $resource = PkptActivityResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
