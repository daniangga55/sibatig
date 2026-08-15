<?php

namespace App\Enums;

enum DocumentCategory: string
{
    case Spt = 'SPT';
    case WorkProgram = 'PROGRAM_KERJA';
    case Report = 'LAPORAN';

    public function label(): string
    {
        return match ($this) {
            self::Spt => 'File SPT',
            self::WorkProgram => 'Program Kerja',
            self::Report => 'Laporan',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Spt => 'primary',
            self::WorkProgram => 'warning',
            self::Report => 'success',
        };
    }

    public function directory(): string
    {
        return match ($this) {
            self::Spt => 'spt',
            self::WorkProgram => 'program-kerja',
            self::Report => 'laporan',
        };
    }

    /** @return array<string, string> */
    public static function options(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn (self $category): array => [$category->value => $category->label()])
            ->all();
    }
}
