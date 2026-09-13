<?php

namespace App\Filament\Resources\NonPkptSupportingDocuments\Pages;

use App\Filament\Resources\NonPkptSupportingDocuments\NonPkptSupportingDocumentResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListNonPkptSupportingDocuments extends ListRecords
{
    protected static string $resource = NonPkptSupportingDocumentResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
