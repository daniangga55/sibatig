<?php

namespace App\Filament\Resources\NonPkptSptRecords\Pages;

use App\Filament\Resources\NonPkptSptRecords\NonPkptSptRecordResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListNonPkptSptRecords extends ListRecords
{
    protected static string $resource = NonPkptSptRecordResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
