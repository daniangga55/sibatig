<?php

namespace App\Filament\Resources\NonPkptSupportingDocuments\Pages;

use App\Filament\Resources\NonPkptSupportingDocuments\NonPkptSupportingDocumentResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewNonPkptSupportingDocument extends ViewRecord
{
    protected static string $resource = NonPkptSupportingDocumentResource::class;

    protected function getHeaderActions(): array
    {
        return [EditAction::make()];
    }
}
