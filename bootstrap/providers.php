<?php

use App\Providers\AppServiceProvider;
use App\Providers\Filament\AdminPanelProvider;
use App\Providers\GoogleDriveServiceProvider;

return [
    AppServiceProvider::class,
    GoogleDriveServiceProvider::class,
    AdminPanelProvider::class,
];
