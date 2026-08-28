<?php

namespace App\Support;

use InvalidArgumentException;

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
