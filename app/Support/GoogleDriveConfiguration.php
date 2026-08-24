<?php

namespace App\Support;

use Google\Client;
use Google\Service\Drive;
use Google\Service\Drive\DriveFile;
use Illuminate\Support\Facades\File;
use RuntimeException;

final class GoogleDriveConfiguration
{
    /** @return array<string, mixed> */
    public static function resolve(): array
    {
        $credentialsPath = self::absolutePath(
            env('GOOGLE_DRIVE_CREDENTIALS_PATH'),
            storage_path('app/credentials/google-drive-oauth.json'),
        );
        $tokenPath = self::absolutePath(
            env('GOOGLE_DRIVE_TOKEN_PATH'),
            storage_path('app/credentials/google-drive-token.json'),
        );
        $folderPath = self::absolutePath(
            env('GOOGLE_DRIVE_FOLDER_PATH'),
            storage_path('app/credentials/google-drive-folder.json'),
        );

        $credentials = self::readJson($credentialsPath);
        $oauth = $credentials['web'] ?? $credentials['installed'] ?? [];
        $token = self::readJson($tokenPath);
        $folder = self::readJson($folderPath);

        return [
            'credentials_path' => $credentialsPath,
            'token_path' => $tokenPath,
            'folder_path' => $folderPath,
            'client_id' => self::firstFilled(env('GOOGLE_DRIVE_CLIENT_ID'), $oauth['client_id'] ?? null),
            'client_secret' => self::firstFilled(env('GOOGLE_DRIVE_CLIENT_SECRET'), $oauth['client_secret'] ?? null),
            'redirect_uri' => self::firstFilled(
                env('GOOGLE_DRIVE_REDIRECT_URI'),
                $oauth['redirect_uris'][0] ?? null,
                rtrim((string) env('APP_URL', 'http://127.0.0.1:8000'), '/').'/admin/google-drive/oauth/callback',
            ),
            'access_token' => self::firstFilled(env('GOOGLE_DRIVE_ACCESS_TOKEN'), $token !== [] ? $token : null),
            'refresh_token' => self::firstFilled(env('GOOGLE_DRIVE_REFRESH_TOKEN'), $token['refresh_token'] ?? null),
            'folder_id' => self::firstFilled(env('GOOGLE_DRIVE_FOLDER_ID'), $folder['folder_id'] ?? null),
            'debug' => filter_var(env('GOOGLE_DRIVE_DEBUG', false), FILTER_VALIDATE_BOOL),
            'log_payload' => filter_var(env('GOOGLE_DRIVE_LOG_PAYLOAD', false), FILTER_VALIDATE_BOOL),
        ];
    }

    public static function oauthClient(): Client
    {
        $configuration = self::resolve();

        if (blank($configuration['client_id']) || blank($configuration['client_secret'])) {
            throw new RuntimeException('Client ID atau Client Secret Google Drive belum tersedia.');
        }

        $client = new Client;
        $client->setClientId($configuration['client_id']);
        $client->setClientSecret($configuration['client_secret']);
        $client->setRedirectUri($configuration['redirect_uri']);
        $client->setAccessType('offline');
        $client->setPrompt('consent');
        $client->setIncludeGrantedScopes(true);
        $client->setScopes([Drive::DRIVE_FILE]);

        return $client;
    }

    /** @param array<string, mixed> $token */
    public static function storeToken(array $token): void
    {
        $configuration = self::resolve();
        $tokenPath = $configuration['token_path'];
        $existingToken = self::readJson($tokenPath);

        if (blank($token['refresh_token'] ?? null) && filled($existingToken['refresh_token'] ?? null)) {
            $token['refresh_token'] = $existingToken['refresh_token'];
        }

        if (blank($token['refresh_token'] ?? null)) {
            throw new RuntimeException('Google tidak mengirim refresh token. Cabut akses aplikasi lalu ulangi otorisasi.');
        }

        File::ensureDirectoryExists(dirname($tokenPath));
        File::put(
            $tokenPath,
            json_encode($token, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR),
            true,
        );
        @chmod($tokenPath, 0600);
    }

    public static function ensureStorageFolder(Client $client): string
    {
        $configuration = self::resolve();

        if (filled($configuration['folder_id'])) {
            return (string) $configuration['folder_id'];
        }

        $service = new Drive($client);
        $escapedName = str_replace(['\\', "'"], ['\\\\', "\\'"], 'SIBATIG');
        $folders = $service->files->listFiles([
            'q' => "name='{$escapedName}' and mimeType='application/vnd.google-apps.folder' and trashed=false",
            'fields' => 'files(id,name)',
            'pageSize' => 1,
            'spaces' => 'drive',
        ])->getFiles();

        $folderId = ($folders[0] ?? null)?->getId();

        if (blank($folderId)) {
            $driveFile = new DriveFile([
                'name' => 'SIBATIG',
                'mimeType' => 'application/vnd.google-apps.folder',
            ]);
            $folderId = $service->files
                ->create($driveFile, ['fields' => 'id'])
                ->getId();
        }

        if (blank($folderId)) {
            throw new RuntimeException('Folder SIBATIG tidak dapat dibuat di Google Drive.');
        }

        $folderPath = $configuration['folder_path'];
        File::ensureDirectoryExists(dirname($folderPath));
        File::put(
            $folderPath,
            json_encode(['folder_id' => $folderId], JSON_PRETTY_PRINT | JSON_THROW_ON_ERROR),
            true,
        );
        @chmod($folderPath, 0600);

        return $folderId;
    }

    /** @return array<string, mixed> */
    private static function readJson(string $path): array
    {
        if (! is_file($path)) {
            return [];
        }

        $decoded = json_decode((string) file_get_contents($path), true);

        return is_array($decoded) ? $decoded : [];
    }

    private static function absolutePath(mixed $path, string $fallback): string
    {
        if (blank($path)) {
            return $fallback;
        }

        $path = (string) $path;

        if (preg_match('/^(?:[A-Za-z]:[\\\\\/]|\/)/', $path) === 1) {
            return $path;
        }

        return base_path($path);
    }

    private static function firstFilled(mixed ...$values): mixed
    {
        foreach ($values as $value) {
            if (filled($value)) {
                return $value;
            }
        }

        return null;
    }
}
