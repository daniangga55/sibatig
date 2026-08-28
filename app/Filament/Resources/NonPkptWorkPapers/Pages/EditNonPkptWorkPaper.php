<?php

namespace App\Filament\Resources\NonPkptWorkPapers\Pages;

use App\Filament\Concerns\HasReliableCancelAction;
use App\Filament\Resources\NonPkptWorkPapers\NonPkptWorkPaperResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditNonPkptWorkPaper extends EditRecord
{
    use HasReliableCancelAction;

    protected static string $resource = NonPkptWorkPaperResource::class;

    protected function getHeaderActions(): array
    {
        return [ViewAction::make(), DeleteAction::make(), ForceDeleteAction::make(), RestoreAction::make()];
    }
}
