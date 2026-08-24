<?php

return [
    // Jangan aktifkan payload logging di production karena dapat memuat metadata file.
    'debug' => env('GOOGLE_DRIVE_DEBUG', false),
    'log_payload' => env('GOOGLE_DRIVE_LOG_PAYLOAD', false),
];
