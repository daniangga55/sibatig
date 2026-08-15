<?php

namespace App\Filament\Resources\TeamMembers\Pages;

use App\Filament\Concerns\HasReliableCancelAction;
use App\Filament\Resources\TeamMembers\TeamMemberResource;
use Filament\Resources\Pages\CreateRecord;

class CreateTeamMember extends CreateRecord
{
    use HasReliableCancelAction;

    protected static string $resource = TeamMemberResource::class;
}
