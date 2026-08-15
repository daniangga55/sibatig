<?php

namespace App\Filament\Resources\SptRecords\Pages;

use App\Filament\Resources\SptRecords\SptRecordResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSptRecords extends ListRecords
{
    protected static string $resource = SptRecordResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
