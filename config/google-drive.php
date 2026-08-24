<?php

use App\Support\GoogleDriveConfiguration;

$googleDrive = GoogleDriveConfiguration::resolve();

return [
    'credentials_path' => $googleDrive['credentials_path'],
    'token_path' => $googleDrive['token_path'],
    'folder_path' => $googleDrive['folder_path'],
    'client_id' => $googleDrive['client_id'],
    'client_secret' => $googleDrive['client_secret'],
    'redirect_uri' => $googleDrive['redirect_uri'],
    'folder_id' => $googleDrive['folder_id'],
    // Jangan aktifkan payload logging di production karena dapat memuat metadata file.
    'debug' => $googleDrive['debug'],
    'log_payload' => $googleDrive['log_payload'],
];
