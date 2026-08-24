<?php

namespace App\Http\Controllers;

use App\Enums\UserRole;
use App\Support\GoogleDriveConfiguration;
use Filament\Notifications\Notification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use RuntimeException;
use Throwable;

class GoogleDriveOAuthController extends Controller
{
    public function authorizeDrive(Request $request): RedirectResponse
    {
        $this->ensureSuperAdmin($request);

        try {
            $client = GoogleDriveConfiguration::oauthClient();
            $state = Str::random(64);

            $request->session()->put('google_drive_oauth_state', $state);
            $client->setState($state);

            return redirect()->away($client->createAuthUrl());
        } catch (Throwable $exception) {
            report($exception);

            Notification::make()
                ->title('Konfigurasi Google Drive belum siap')
                ->body($exception->getMessage())
                ->danger()
                ->send();

            return redirect('/admin');
        }
    }

    public function callback(Request $request): RedirectResponse
    {
        $this->ensureSuperAdmin($request);

        try {
            if ($request->filled('error')) {
                throw new RuntimeException('Otorisasi dibatalkan atau ditolak oleh Google.');
            }

            $expectedState = (string) $request->session()->pull('google_drive_oauth_state');
            $returnedState = (string) $request->query('state');

            if (blank($expectedState) || ! hash_equals($expectedState, $returnedState)) {
                throw new RuntimeException('State OAuth tidak valid atau sesi telah kedaluwarsa.');
            }

            $code = (string) $request->query('code');

            if (blank($code)) {
                throw new RuntimeException('Kode otorisasi Google tidak ditemukan.');
            }

            $client = GoogleDriveConfiguration::oauthClient();
            $token = $client->fetchAccessTokenWithAuthCode($code);

            if (isset($token['error'])) {
                throw new RuntimeException((string) ($token['error_description'] ?? $token['error']));
            }

            GoogleDriveConfiguration::storeToken($token);
            GoogleDriveConfiguration::ensureStorageFolder($client);

            Notification::make()
                ->title('Google Drive berhasil diotorisasi')
                ->body('Refresh token dan Folder ID SIBATIG tersimpan secara lokal dan tidak dimasukkan ke Git.')
                ->success()
                ->send();
        } catch (Throwable $exception) {
            report($exception);

            Notification::make()
                ->title('Otorisasi Google Drive gagal')
                ->body($exception->getMessage())
                ->danger()
                ->send();
        }

        return redirect('/admin');
    }

    private function ensureSuperAdmin(Request $request): void
    {
        abort_unless($request->user()?->hasAnyRole(UserRole::SuperAdmin), 403);
    }
}
