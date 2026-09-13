<?php

namespace App\Filament\Resources\NonPkptSupportingDocuments\Pages;

use App\Filament\Concerns\HasReliableCancelAction;
use App\Filament\Resources\NonPkptSupportingDocuments\NonPkptSupportingDocumentResource;
use Filament\Resources\Pages\CreateRecord;

class CreateNonPkptSupportingDocument extends CreateRecord
{
    use HasReliableCancelAction;

    protected static string $resource = NonPkptSupportingDocumentResource::class;
}
