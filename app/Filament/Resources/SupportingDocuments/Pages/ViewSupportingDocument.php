<?php

namespace App\Filament\Resources\SupportingDocuments\Pages;

use App\Filament\Resources\SupportingDocuments\SupportingDocumentResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewSupportingDocument extends ViewRecord
{
    protected static string $resource = SupportingDocumentResource::class;

    protected function getHeaderActions(): array
    {
        return [EditAction::make()];
    }
}
