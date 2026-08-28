<?php

use App\Providers\AppServiceProvider;
use App\Providers\Filament\AdminPanelProvider;
use Klytron\GoogleDriveFilesystem\Providers\GoogleDriveServiceProvider;

return [
    AppServiceProvider::class,
    // Explicit registration keeps the Google disk available when aaPanel
    // deployments rebuild Laravel's package-discovery cache inconsistently.
    GoogleDriveServiceProvider::class,
    AdminPanelProvider::class,
];
