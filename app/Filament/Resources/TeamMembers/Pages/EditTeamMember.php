<?php

namespace App\Filament\Resources\TeamMembers\Pages;

use App\Filament\Concerns\HasReliableCancelAction;
use App\Filament\Resources\TeamMembers\TeamMemberResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditTeamMember extends EditRecord
{
    use HasReliableCancelAction;

    protected static string $resource = TeamMemberResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
