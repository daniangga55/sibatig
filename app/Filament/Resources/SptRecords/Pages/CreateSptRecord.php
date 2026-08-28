<?php

namespace App\Filament\Resources\SptRecords\Pages;

use App\Filament\Concerns\HasReliableCancelAction;
use App\Filament\Resources\SptRecords\Pages\Concerns\HasSequentialSptTabs;
use App\Filament\Resources\SptRecords\SptRecordResource;
use App\Support\SptDocumentSync;
use Filament\Actions\Action;
use Filament\Resources\Pages\CreateRecord;

class CreateSptRecord extends CreateRecord
{
    use HasReliableCancelAction;
    use HasSequentialSptTabs;

    protected static string $resource = SptRecordResource::class;

    protected ?string $sptDocumentPath = null;

    protected ?string $sptDocumentOriginalName = null;

    protected function getCreateFormAction(): Action
    {
        return parent::getCreateFormAction()
            ->label('Simpan SPT')
            ->visible(fn (): bool => $this->canAccessSptTab(3));
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $this->sptDocumentPath = SptDocumentSync::pathFromState($data['spt_file'] ?? null);
        $this->sptDocumentOriginalName = $data['spt_file_original_name'] ?? null;
        $data['relation_type'] = 'PKPT';
        $data['non_pkpt_activity_id'] = null;

        unset($data['spt_file'], $data['spt_file_original_name']);

        return $data;
    }

    protected function afterCreate(): void
    {
        SptDocumentSync::sync(
            $this->getRecord(),
            $this->sptDocumentPath,
            $this->sptDocumentOriginalName,
            auth()->id(),
        );
    }
}
