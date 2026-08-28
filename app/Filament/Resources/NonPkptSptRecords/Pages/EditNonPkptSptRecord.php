<?php

namespace App\Filament\Resources\NonPkptSptRecords\Pages;

use App\Filament\Concerns\HasReliableCancelAction;
use App\Filament\Resources\NonPkptSptRecords\NonPkptSptRecordResource;
use App\Filament\Resources\SptRecords\Pages\Concerns\HasSequentialSptTabs;
use App\Support\SptDocumentSync;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditNonPkptSptRecord extends EditRecord
{
    use HasReliableCancelAction;
    use HasSequentialSptTabs;

    protected static string $resource = NonPkptSptRecordResource::class;

    protected ?string $sptDocumentPath = null;

    protected ?string $sptDocumentOriginalName = null;

    protected function getSaveFormAction(): Action
    {
        return parent::getSaveFormAction()->label('Simpan Perubahan')->visible(fn (): bool => $this->canAccessSptTab(3));
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $document = SptDocumentSync::documentFor($this->getRecord());
        $data['spt_file'] = $document?->file_path;
        $data['spt_file_original_name'] = $document?->original_name;

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $this->sptDocumentPath = SptDocumentSync::pathFromState($data['spt_file'] ?? null);
        $this->sptDocumentOriginalName = $data['spt_file_original_name'] ?? null;
        $data['relation_type'] = 'NON PKPT';
        $data['pkpt_activity_id'] = null;
        unset($data['spt_file'], $data['spt_file_original_name']);

        return $data;
    }

    protected function afterSave(): void
    {
        SptDocumentSync::sync($this->getRecord(), $this->sptDocumentPath, $this->sptDocumentOriginalName, auth()->id());
    }

    protected function getHeaderActions(): array
    {
        return [ViewAction::make(), DeleteAction::make(), ForceDeleteAction::make(), RestoreAction::make()];
    }
}
