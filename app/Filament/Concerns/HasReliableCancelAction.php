<?php

namespace App\Filament\Concerns;

use Filament\Actions\Action;

trait HasReliableCancelAction
{
    protected function getCancelFormAction(): Action
    {
        return Action::make('cancel')
            ->label('Batal')
            ->url(static::getResource()::getUrl('index'))
            ->color('gray');
    }
}
