<?php

namespace App\Filament\Resources\WebsiteSettings\Pages;

use App\Filament\Concerns\HasReliableCancelAction;
use App\Filament\Resources\WebsiteSettings\WebsiteSettingResource;
use Filament\Resources\Pages\CreateRecord;

class CreateWebsiteSetting extends CreateRecord
{
    use HasReliableCancelAction;

    protected static string $resource = WebsiteSettingResource::class;
}
