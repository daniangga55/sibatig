<?php

namespace App\Filament\Resources\Documents\Pages;

use App\Filament\Resources\Documents\DocumentResource;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;
use Filament\Support\Icons\Heroicon;

class ViewDocument extends ViewRecord
{
    protected static string $resource = DocumentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('download')
                ->label('Unduh File')
                ->icon(Heroicon::OutlinedArrowDownTray)
                ->color('success')
                ->url(fn (): string => route('documents.download', $this->getRecord()))
                ->openUrlInNewTab(),
            EditAction::make(),
        ];
    }
}
