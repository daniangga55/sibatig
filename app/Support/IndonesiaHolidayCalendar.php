<?php

namespace App\Support;

use Carbon\CarbonImmutable;
use Carbon\CarbonInterface;

final class IndonesiaHolidayCalendar
{
    public const SOURCE_URL = 'https://setneg.go.id/baca/index/inilah_skb_3_menteri_libur_nasional_dan_cuti_bersama_2026';

    /** @var array<string, array{name: string, type: 'national'|'collective'}> */
    private const HOLIDAYS = [
        '2026-01-01' => ['name' => 'Tahun Baru 2026 Masehi', 'type' => 'national'],
        '2026-01-16' => ['name' => 'Isra Mikraj Nabi Muhammad saw.', 'type' => 'national'],
        '2026-02-16' => ['name' => 'Cuti Bersama Tahun Baru Imlek', 'type' => 'collective'],
        '2026-02-17' => ['name' => 'Tahun Baru Imlek 2577 Kongzili', 'type' => 'national'],
        '2026-03-18' => ['name' => 'Cuti Bersama Hari Suci Nyepi', 'type' => 'collective'],
        '2026-03-19' => ['name' => 'Hari Suci Nyepi (Tahun Baru Saka 1948)', 'type' => 'national'],
        '2026-03-20' => ['name' => 'Cuti Bersama Idulfitri 1447 H', 'type' => 'collective'],
        '2026-03-21' => ['name' => 'Idulfitri 1447 H', 'type' => 'national'],
        '2026-03-22' => ['name' => 'Idulfitri 1447 H', 'type' => 'national'],
        '2026-03-23' => ['name' => 'Cuti Bersama Idulfitri 1447 H', 'type' => 'collective'],
        '2026-03-24' => ['name' => 'Cuti Bersama Idulfitri 1447 H', 'type' => 'collective'],
        '2026-04-03' => ['name' => 'Wafat Yesus Kristus', 'type' => 'national'],
        '2026-04-05' => ['name' => 'Kebangkitan Yesus Kristus (Paskah)', 'type' => 'national'],
        '2026-05-01' => ['name' => 'Hari Buruh Internasional', 'type' => 'national'],
        '2026-05-14' => ['name' => 'Kenaikan Yesus Kristus', 'type' => 'national'],
        '2026-05-15' => ['name' => 'Cuti Bersama Kenaikan Yesus Kristus', 'type' => 'collective'],
        '2026-05-27' => ['name' => 'Iduladha 1447 H', 'type' => 'national'],
        '2026-05-28' => ['name' => 'Cuti Bersama Iduladha 1447 H', 'type' => 'collective'],
        '2026-05-31' => ['name' => 'Hari Raya Waisak 2570 BE', 'type' => 'national'],
        '2026-06-01' => ['name' => 'Hari Lahir Pancasila', 'type' => 'national'],
        '2026-06-16' => ['name' => '1 Muharam Tahun Baru Islam 1448 H', 'type' => 'national'],
        '2026-08-17' => ['name' => 'Proklamasi Kemerdekaan', 'type' => 'national'],
        '2026-08-25' => ['name' => 'Maulid Nabi Muhammad saw.', 'type' => 'national'],
        '2026-12-24' => ['name' => 'Cuti Bersama Kelahiran Yesus Kristus', 'type' => 'collective'],
        '2026-12-25' => ['name' => 'Kelahiran Yesus Kristus', 'type' => 'national'],
    ];

    /** @return array<string, array{name: string, type: 'national'|'collective'}> */
    public static function forYear(int $year): array
    {
        return array_filter(
            self::HOLIDAYS,
            fn (string $date): bool => str_starts_with($date, "{$year}-"),
            ARRAY_FILTER_USE_KEY,
        );
    }

    /** @return array{name: string, type: 'national'|'collective'}|null */
    public static function holiday(CarbonInterface|string $date): ?array
    {
        return self::HOLIDAYS[self::normalise($date)->toDateString()] ?? null;
    }

    public static function isWorkingDay(CarbonInterface|string $date): bool
    {
        $date = self::normalise($date);

        return ! $date->isWeekend() && self::holiday($date) === null;
    }

    /** @return array<int, string> */
    public static function nonWorkingDates(int $year): array
    {
        $date = CarbonImmutable::create($year, 1, 1);
        $dates = [];

        while ($date->year === $year) {
            if (! self::isWorkingDay($date)) {
                $dates[] = $date->toDateString();
            }

            $date = $date->addDay();
        }

        return $dates;
    }

    public static function workingDaysInMonth(int $year, int $month): int
    {
        $date = CarbonImmutable::create($year, $month, 1);
        $lastDay = $date->endOfMonth();
        $count = 0;

        while ($date->lte($lastDay)) {
            $count += self::isWorkingDay($date) ? 1 : 0;
            $date = $date->addDay();
        }

        return $count;
    }

    private static function normalise(CarbonInterface|string $date): CarbonImmutable
    {
        return $date instanceof CarbonInterface
            ? CarbonImmutable::instance($date)
            : CarbonImmutable::parse($date);
    }
}
