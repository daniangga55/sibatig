<?php

namespace App\Console\Commands;

use App\Support\GoogleDriveStorage;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use RuntimeException;
use Throwable;

class CheckGoogleDriveStorage extends Command
{
    protected $signature = 'sibatig:gdrive-check
        {--write : Siapkan folder lalu uji unggah, baca, dan hapus file sementara}
        {--year=2026 : Tahun folder dokumen yang akan disiapkan}';

    protected $description = 'Memeriksa konfigurasi dan akses penyimpanan Google Drive SIBATIG';

    public function handle(): int
    {
        $required = [
            'GOOGLE_DRIVE_CLIENT_ID' => config('filesystems.disks.google.clientId'),
            'GOOGLE_DRIVE_CLIENT_SECRET' => config('filesystems.disks.google.clientSecret'),
            'GOOGLE_DRIVE_REFRESH_TOKEN' => config('filesystems.disks.google.refreshToken'),
            'GOOGLE_DRIVE_FOLDER' => config('filesystems.disks.google.folder'),
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

            $year = (string) $this->option('year');
            $directories = [
                GoogleDriveStorage::path(GoogleDriveStorage::PKPT, GoogleDriveStorage::SPT, $year),
                GoogleDriveStorage::path(GoogleDriveStorage::PKPT, GoogleDriveStorage::WORK_PAPER, $year),
                GoogleDriveStorage::path(GoogleDriveStorage::PKPT, GoogleDriveStorage::REPORT, $year),
                GoogleDriveStorage::path(GoogleDriveStorage::NON_PKPT, GoogleDriveStorage::SPT, $year),
                GoogleDriveStorage::path(GoogleDriveStorage::NON_PKPT, GoogleDriveStorage::WORK_PAPER, $year),
                GoogleDriveStorage::path(GoogleDriveStorage::NON_PKPT, GoogleDriveStorage::REPORT, $year),
            ];
            $testPaths = [];

            try {
                $contents = 'SIBATIG Google Drive check '.now()->toIso8601String();

                foreach ($directories as $directory) {
                    $this->line("Menyiapkan folder: SIBATIG/{$directory}");
                    $disk->makeDirectory($directory);

                    if (! $disk->directoryExists($directory)) {
                        throw new RuntimeException("Folder [SIBATIG/{$directory}] tidak dapat diverifikasi.");
                    }

                    $path = $directory.'/.sibatig-health-check-'.Str::uuid().'.txt';
                    $testPaths[] = $path;
                    $disk->put($path, $contents);

                    if (! $disk->exists($path) || $disk->get($path) !== $contents) {
                        throw new RuntimeException("File uji [SIBATIG/{$path}] tidak dapat diverifikasi.");
                    }
                }

                if ($testPaths === []) {
                    $this->error('Tidak ada folder yang diuji.');

                    return self::FAILURE;
                }
            } finally {
                foreach ($testPaths as $path) {
                    if ($disk->exists($path)) {
                        $disk->delete($path);
                    }
                }
            }

            $this->info('Seluruh folder dokumen berhasil disiapkan. Uji unggah, baca, dan hapus berhasil.');

            return self::SUCCESS;
        } catch (Throwable $exception) {
            $this->error('Google Drive belum dapat diakses: '.$exception->getMessage());

            return self::FAILURE;
        }
    }
}
