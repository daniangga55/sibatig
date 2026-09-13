<?php

namespace App\Filament\Resources\SupportingDocuments\Pages;

use App\Filament\Concerns\HasReliableCancelAction;
use App\Filament\Resources\SupportingDocuments\SupportingDocumentResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditSupportingDocument extends EditRecord
{
    use HasReliableCancelAction;

    protected static string $resource = SupportingDocumentResource::class;

    protected function getHeaderActions(): array
    {
        return [ViewAction::make(), DeleteAction::make(), ForceDeleteAction::make(), RestoreAction::make()];
    }
}
