<?php

namespace App\Filament\Resources\SupportingDocuments\Pages;

use App\Filament\Concerns\HasReliableCancelAction;
use App\Filament\Resources\SupportingDocuments\SupportingDocumentResource;
use Filament\Resources\Pages\CreateRecord;

class CreateSupportingDocument extends CreateRecord
{
    use HasReliableCancelAction;

    protected static string $resource = SupportingDocumentResource::class;
}
