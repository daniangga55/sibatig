<?php

namespace App\Support;

class GoogleDriveStorage
{
    public static function path(string $resource, ?string $subfolder = null): string
    {
        $parts = [$resource];

        if ($subfolder) {
            $parts[] = $subfolder;
        }

        return implode('/', $parts);
    }
}