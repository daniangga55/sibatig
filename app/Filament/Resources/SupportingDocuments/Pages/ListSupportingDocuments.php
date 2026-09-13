<?php

namespace App\Filament\Resources\SupportingDocuments\Pages;

use App\Filament\Resources\SupportingDocuments\SupportingDocumentResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSupportingDocuments extends ListRecords
{
    protected static string $resource = SupportingDocumentResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
