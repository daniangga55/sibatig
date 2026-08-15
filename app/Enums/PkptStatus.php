<?php

namespace App\Enums;

enum PkptStatus: string
{
    case BelumDilaksanakan = 'belum_dilaksanakan';
    case Persiapan = 'persiapan';
    case Berjalan = 'sedang_berjalan';
    case PenyusunanLaporan = 'penyusunan_laporan';
    case Selesai = 'selesai';
    case Terkendala = 'terkendala';

    public function label(): string
    {
        return match ($this) {
            self::BelumDilaksanakan => 'Belum Dilaksanakan',
            self::Persiapan => 'Persiapan',
            self::Berjalan => 'Sedang Berjalan',
            self::PenyusunanLaporan => 'Penyusunan Laporan',
            self::Selesai => 'Selesai',
            self::Terkendala => 'Terkendala',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::BelumDilaksanakan => 'gray',
            self::Persiapan => 'info',
            self::Berjalan => 'warning',
            self::PenyusunanLaporan => 'primary',
            self::Selesai => 'success',
            self::Terkendala => 'danger',
        };
    }

    public static function options(): array
    {
        return collect(self::cases())->mapWithKeys(fn (self $status): array => [$status->value => $status->label()])->all();
    }
}
