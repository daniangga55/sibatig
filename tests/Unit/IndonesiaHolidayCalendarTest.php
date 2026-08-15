<?php

namespace Tests\Unit;

use App\Rules\WorkingDay;
use App\Support\IndonesiaHolidayCalendar;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class IndonesiaHolidayCalendarTest extends TestCase
{
    public function test_official_2026_holidays_are_available(): void
    {
        $holidays = collect(IndonesiaHolidayCalendar::forYear(2026));

        $this->assertCount(17, $holidays->where('type', 'national'));
        $this->assertCount(8, $holidays->where('type', 'collective'));
        $this->assertSame('Proklamasi Kemerdekaan', IndonesiaHolidayCalendar::holiday('2026-08-17')['name']);
    }

    public function test_working_days_exclude_weekends_and_official_holidays(): void
    {
        $this->assertTrue(IndonesiaHolidayCalendar::isWorkingDay('2026-08-14'));
        $this->assertFalse(IndonesiaHolidayCalendar::isWorkingDay('2026-08-15'));
        $this->assertFalse(IndonesiaHolidayCalendar::isWorkingDay('2026-08-17'));
    }

    public function test_working_day_rule_rejects_weekends_and_holidays(): void
    {
        $weekend = Validator::make(['date' => '2026-08-15'], ['date' => [new WorkingDay]]);
        $holiday = Validator::make(['date' => '2026-08-17'], ['date' => [new WorkingDay]]);
        $workingDay = Validator::make(['date' => '2026-08-14'], ['date' => [new WorkingDay]]);

        $this->assertTrue($weekend->fails());
        $this->assertTrue($holiday->fails());
        $this->assertFalse($workingDay->fails());
    }
}
