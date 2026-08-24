<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Throwable;

class CheckGoogleDriveStorage extends Command
{
    protected $signature = 'sibatig:gdrive-check
        {--write : Uji unggah, baca, dan hapus satu file sementara}';

    protected $description = 'Memeriksa konfigurasi dan akses penyimpanan Google Drive SIBATIG';

    public function handle(): int
    {
        $required = [
            'GOOGLE_DRIVE_CLIENT_ID' => config('filesystems.disks.google.client_id'),
            'GOOGLE_DRIVE_CLIENT_SECRET' => config('filesystems.disks.google.client_secret'),
            'GOOGLE_DRIVE_REFRESH_TOKEN / GOOGLE_DRIVE_ACCESS_TOKEN' => config('filesystems.disks.google.refresh_token') ?: config('filesystems.disks.google.access_token'),
            'GOOGLE_DRIVE_FOLDER_ID' => config('filesystems.disks.google.folder_id'),
        ];

        $missing = array_keys(array_filter($required, fn (mixed $value): bool => blank($value)));

        if ($missing !== []) {
            $this->error('Konfigurasi Google Drive belum lengkap:');

            foreach ($missing as $key) {
                $this->line("  - {$key}");
            }

            $this->newLine();
            $this->line('Lengkapi file kredensial/token atau .env, lalu jalankan `php artisan optimize:clear`.');

            return self::FAILURE;
        }

        try {
            $disk = Storage::disk('google');

            if (! $disk->directoryExists('')) {
                $this->error('Folder Google Drive tidak ditemukan atau akun tidak memiliki akses.');

                return self::FAILURE;
            }

            $this->info('Autentikasi dan folder Google Drive berhasil diakses.');

            if (! $this->option('write')) {
                $this->line('Jalankan kembali dengan --write untuk menguji izin unggah dan hapus.');

                return self::SUCCESS;
            }

            $path = '.sibatig-health-check-'.Str::uuid().'.txt';

            try {
                $contents = 'SIBATIG Google Drive check '.now()->toIso8601String();
                $disk->put($path, $contents);

                if (! $disk->exists($path) || $disk->get($path) !== $contents) {
                    $this->error('File uji tidak dapat diverifikasi setelah diunggah.');

                    return self::FAILURE;
                }
            } finally {
                if (isset($path) && $disk->exists($path)) {
                    $disk->delete($path);
                }
            }

            $this->info('Uji unggah, baca, dan hapus berhasil. Google Drive siap digunakan.');

            return self::SUCCESS;
        } catch (Throwable $exception) {
            $this->error('Google Drive belum dapat diakses: '.$exception->getMessage());

            return self::FAILURE;
        }
    }
}
