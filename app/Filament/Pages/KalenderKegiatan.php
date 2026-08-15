<?php

namespace App\Filament\Pages;

use App\Filament\Resources\SptRecords\SptRecordResource;
use App\Models\SptRecord;
use App\Support\IndonesiaHolidayCalendar;
use BackedEnum;
use Carbon\Carbon;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Collection;
use UnitEnum;

class KalenderKegiatan extends Page
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCalendarDays;

    protected static ?string $navigationLabel = 'Kalender Kegiatan';

    protected static string|UnitEnum|null $navigationGroup = 'Menu Utama';

    protected static ?int $navigationSort = 50;

    protected static ?string $title = 'Kalender Kegiatan';

    protected string $view = 'filament.pages.kalender-kegiatan';

    public int $month;

    public int $year;

    public string $selectedDate;

    public bool $agendaVisible = false;

    public function mount(): void
    {
        $activeYear = 2026;
        $today = now()->year === $activeYear ? now() : Carbon::create($activeYear, 1, 1);
        $requestedDate = request()->query('tanggal');

        if (is_string($requestedDate) && preg_match('/^2026-\d{2}-\d{2}$/', $requestedDate)) {
            $calendarDate = rescue(
                fn (): Carbon => Carbon::createFromFormat('Y-m-d', $requestedDate),
                null,
                report: false,
            );

            if ($calendarDate instanceof Carbon && $calendarDate->toDateString() === $requestedDate) {
                $this->month = $calendarDate->month;
                $this->year = $activeYear;
                $this->selectedDate = $calendarDate->toDateString();
                $this->agendaVisible = true;

                return;
            }
        }

        $this->month = $today->month;
        $this->year = $activeYear;
        $this->selectedDate = $today->toDateString();
    }

    public function getHeading(): string
    {
        return '';
    }

    public function getSubheading(): ?string
    {
        return null;
    }

    public function previousMonth(): void
    {
        if ($this->month > 1) {
            $this->setPeriod(Carbon::create($this->year, $this->month, 1)->subMonth());
        }
    }

    public function nextMonth(): void
    {
        if ($this->month < 12) {
            $this->setPeriod(Carbon::create($this->year, $this->month, 1)->addMonth());
        }
    }

    public function updatedMonth(): void
    {
        $this->selectedDate = Carbon::create($this->year, $this->month, 1)->toDateString();
        $this->agendaVisible = false;
    }

    public function updatedYear(): void
    {
        $this->selectedDate = Carbon::create($this->year, $this->month, 1)->toDateString();
        $this->agendaVisible = false;
    }

    public function selectDate(string $date): void
    {
        $selected = Carbon::parse($date);

        if ($this->agendaVisible && $this->selectedDate === $selected->toDateString()) {
            $this->agendaVisible = false;

            return;
        }

        $this->selectedDate = $selected->toDateString();
        $this->agendaVisible = true;

        if ($selected->month !== $this->month || $selected->year !== $this->year) {
            $this->month = $selected->month;
            $this->year = $selected->year;
        }
    }

    protected function getViewData(): array
    {
        $records = SptRecord::query()
            ->where('year', $this->year)
            ->orderBy('start_date')
            ->get();
        $periodStart = Carbon::create($this->year, $this->month, 1);
        $calendarStart = $periodStart->copy()->startOfWeek(Carbon::MONDAY);
        $selected = Carbon::parse($this->selectedDate);

        $days = collect(range(0, 41))->map(function (int $offset) use ($calendarStart, $records, $selected): array {
            $date = $calendarStart->copy()->addDays($offset);
            $activeRecords = $this->recordsForDate($records, $date);
            $holiday = IndonesiaHolidayCalendar::holiday($date);

            return [
                'date' => $date->toDateString(),
                'day' => $date->day,
                'currentMonth' => $date->month === $this->month,
                'today' => $date->isToday(),
                'selected' => $this->agendaVisible && $date->isSameDay($selected),
                'weekend' => $date->isWeekend(),
                'workingDay' => IndonesiaHolidayCalendar::isWorkingDay($date),
                'holiday' => $holiday,
                'count' => $activeRecords->count(),
                'types' => $activeRecords->pluck('assignment_type')->unique()->values(),
            ];
        });
        $selectedRecords = $this->recordsForDate($records, $selected);
        $firstDate = $records->min('start_date');
        $lastDate = $records->max(fn (SptRecord $record) => ($record->end_date ?? $record->start_date)->getTimestamp());
        $lastDate = $lastDate ? Carbon::createFromTimestamp($lastDate) : null;

        return [
            'records' => $records,
            'days' => $days,
            'selected' => $selected,
            'agendaVisible' => $this->agendaVisible,
            'selectedHoliday' => IndonesiaHolidayCalendar::holiday($selected),
            'selectedIsWeekend' => $selected->isWeekend(),
            'selectedRecords' => $selectedRecords,
            'monthLabel' => $periodStart->locale('id')->translatedFormat('F Y'),
            'monthOptions' => collect(range(1, 12))->mapWithKeys(fn (int $month): array => [
                $month => Carbon::create($this->year, $month, 1)->locale('id')->translatedFormat('F'),
            ]),
            'yearOptions' => [2026],
            'completedCount' => $records->where('status', 'SELESAI')->count(),
            'progressCount' => $records->where('status', 'ON PROGRES')->count(),
            'workingDayCount' => IndonesiaHolidayCalendar::workingDaysInMonth($this->year, $this->month),
            'periodLabel' => $firstDate && $lastDate
                ? Carbon::parse($firstDate)->locale('id')->translatedFormat('M').'–'.$lastDate->locale('id')->translatedFormat('M')
                : '—',
            'sptUrl' => SptRecordResource::getUrl('index'),
            'holidaySourceUrl' => IndonesiaHolidayCalendar::SOURCE_URL,
        ];
    }

    /** @param Collection<int, SptRecord> $records */
    private function recordsForDate(Collection $records, Carbon $date): Collection
    {
        if (! IndonesiaHolidayCalendar::isWorkingDay($date)) {
            return collect();
        }

        return $records->filter(function (SptRecord $record) use ($date): bool {
            $endDate = $record->end_date ?? $record->start_date;

            return $record->start_date->copy()->startOfDay()->lte($date)
                && $endDate->copy()->endOfDay()->gte($date);
        })->values();
    }

    private function setPeriod(Carbon $date): void
    {
        $this->month = $date->month;
        $this->year = $date->year;
        $this->selectedDate = $date->toDateString();
        $this->agendaVisible = false;
    }
}
