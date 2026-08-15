<?php

namespace App\Filament\Resources\Documents\Pages;

use App\Filament\Concerns\HasReliableCancelAction;
use App\Filament\Resources\Documents\DocumentResource;
use Filament\Resources\Pages\CreateRecord;

class CreateDocument extends CreateRecord
{
    use HasReliableCancelAction;

    protected static string $resource = DocumentResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['uploaded_by'] = auth()->id();

        return $data;
    }
}
