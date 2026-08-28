<?php

namespace App\Filament\Resources\NonPkptSptRecords\Pages;

use App\Filament\Pages\KalenderKegiatan;
use App\Filament\Resources\NonPkptSptRecords\NonPkptSptRecordResource;
use App\Support\SptDocumentSync;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;
use Filament\Support\Icons\Heroicon;

class ViewNonPkptSptRecord extends ViewRecord
{
    protected static string $resource = NonPkptSptRecordResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('downloadSpt')
                ->label('Unduh File SPT')
                ->icon(Heroicon::OutlinedArrowDownTray)
                ->color('success')
                ->url(function (): string {
                    $document = SptDocumentSync::documentFor($this->getRecord());

                    return $document ? route('documents.download', $document) : '#';
                })
                ->openUrlInNewTab()
                ->visible(fn (): bool => SptDocumentSync::documentFor($this->getRecord()) !== null),
            Action::make('calendar')
                ->label('Lihat di Kalender')
                ->icon(Heroicon::OutlinedCalendarDays)
                ->color('info')
                ->url(fn (): string => KalenderKegiatan::getUrl(['tanggal' => $this->record->start_date->toDateString()]))
                ->extraAttributes(['wire:navigate' => '']),
            EditAction::make(),
        ];
    }
}
