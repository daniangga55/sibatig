<?php

namespace App\Filament\Resources\WorkPapers\Pages;

use App\Filament\Concerns\HasReliableCancelAction;
use App\Filament\Resources\WorkPapers\WorkPaperResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditWorkPaper extends EditRecord
{
    use HasReliableCancelAction;

    protected static string $resource = WorkPaperResource::class;

    protected function getHeaderActions(): array
    {
        return [ViewAction::make(), DeleteAction::make(), ForceDeleteAction::make(), RestoreAction::make()];
    }
}
