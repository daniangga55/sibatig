<?php

namespace App\Providers;

use Google\Client;
use Google\Service\Drive;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\ServiceProvider;
use League\Flysystem\Filesystem;
use Masbug\Flysystem\GoogleDriveAdapter;
use RuntimeException;

class GoogleDriveServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Storage::extend('google', function ($app, array $config): FilesystemAdapter {
            foreach (['clientId', 'clientSecret', 'refreshToken', 'folder'] as $key) {
                if (blank($config[$key] ?? null)) {
                    throw new RuntimeException("Konfigurasi Google Drive [{$key}] belum tersedia.");
                }
            }

            $options = [];

            if (filled($config['teamDriveId'] ?? null)) {
                $options['teamDriveId'] = $config['teamDriveId'];
            }

            if (filled($config['sharedFolderId'] ?? null)) {
                $options['sharedFolderId'] = $config['sharedFolderId'];
            }

            $client = new Client;
            $client->setClientId($config['clientId']);
            $client->setClientSecret($config['clientSecret']);
            $client->refreshToken($config['refreshToken']);
            $client->setApplicationName(config('app.name', 'SIBATIG'));

            $adapter = new GoogleDriveAdapter(
                new Drive($client),
                trim((string) $config['folder'], '/'),
                $options,
            );
            $filesystem = new Filesystem($adapter);

            return new FilesystemAdapter($filesystem, $adapter, $config);
        });
    }
}
