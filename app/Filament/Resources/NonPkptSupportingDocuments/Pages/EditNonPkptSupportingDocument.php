<?php

namespace App\Filament\Resources\NonPkptSupportingDocuments\Pages;

use App\Filament\Concerns\HasReliableCancelAction;
use App\Filament\Resources\NonPkptSupportingDocuments\NonPkptSupportingDocumentResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditNonPkptSupportingDocument extends EditRecord
{
    use HasReliableCancelAction;

    protected static string $resource = NonPkptSupportingDocumentResource::class;

    protected function getHeaderActions(): array
    {
        return [ViewAction::make(), DeleteAction::make(), ForceDeleteAction::make(), RestoreAction::make()];
    }
}
