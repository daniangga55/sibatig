<?php

namespace App\Support;

use Illuminate\Support\Facades\Storage;
use InvalidArgumentException;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

final class GoogleDriveStorage
{
    public const PKPT = 'PKPT';

    public const NON_PKPT = 'NON PKPT';

    public const SPT = 'SPT';

    public const WORK_PAPER = 'KERTAS KERJA';

    public const REPORT = 'LAPORAN';

    /**
     * Menghasilkan path relatif terhadap root GOOGLE_DRIVE_FOLDER=SIBATIG.
     * Path ini juga aman dipakai pada disk lokal.
     */
    public static function path(string $scope, string $documentType, int|string|null $year = null): string
    {
        $scope = self::normalizeScope($scope);
        $documentType = self::normalizeDocumentType($documentType);
        $year = self::normalizeYear($year);

        return "{$scope}/{$documentType}/{$year}";
    }

    /**
     * Pastikan seluruh folder tujuan tersedia sebelum file dikirim ke disk.
     * Masbug juga dapat membuat remote folder otomatis, tetapi langkah eksplisit
     * ini menghasilkan kegagalan yang lebih jelas jika akun tidak memiliki izin.
     */
    public static function storeUploadedFile(
        TemporaryUploadedFile $file,
        string $disk,
        string $directory,
    ): ?string {
        if (! $file->exists()) {
            return null;
        }

        $storage = Storage::disk($disk);
        // Masbug membuat parent secara rekursif dan mengembalikan sukses bila
        // direktori sudah ada. Hindari directoryExists() sebelum pembuatan,
        // karena lookup display path yang belum ada dapat gagal lebih awal.
        $storage->makeDirectory($directory);

        $originalName = basename(str_replace('\\', '/', $file->getClientOriginalName()));

        return $file->storeAs($directory, $originalName, $disk);
    }

    private static function normalizeScope(string $scope): string
    {
        $scope = strtoupper(str_replace(['_', '-'], ' ', trim($scope)));
        $scope = preg_replace('/\s+/', ' ', $scope) ?: '';

        return match ($scope) {
            self::PKPT => self::PKPT,
            self::NON_PKPT, 'NONPKPT' => self::NON_PKPT,
            default => throw new InvalidArgumentException("Jenis penugasan [{$scope}] tidak didukung."),
        };
    }

    private static function normalizeDocumentType(string $documentType): string
    {
        $documentType = strtoupper(str_replace(['_', '-'], ' ', trim($documentType)));
        $documentType = preg_replace('/\s+/', ' ', $documentType) ?: '';

        return match ($documentType) {
            self::SPT => self::SPT,
            self::WORK_PAPER, 'WORK PAPER', 'WORK PAPERS' => self::WORK_PAPER,
            self::REPORT, 'LAPORAN HASIL PENUGASAN', 'ASSIGNMENT REPORT', 'ASSIGNMENT REPORTS' => self::REPORT,
            default => throw new InvalidArgumentException("Jenis dokumen [{$documentType}] tidak didukung."),
        };
    }

    private static function normalizeYear(int|string|null $year): string
    {
        $year = filled($year) ? (string) $year : date('Y');

        if (preg_match('/^\d{4}$/', $year) !== 1) {
            throw new InvalidArgumentException("Tahun dokumen [{$year}] tidak valid.");
        }

        return $year;
    }
}
