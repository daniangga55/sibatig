<?php

namespace App\Rules;

use App\Support\IndonesiaHolidayCalendar;
use Carbon\CarbonImmutable;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Throwable;

class WorkingDay implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        try {
            $date = CarbonImmutable::parse($value);
        } catch (Throwable) {
            return;
        }

        if ($date->isWeekend()) {
            $fail('Tanggal pelaksanaan harus berada pada hari kerja Senin sampai Jumat.');

            return;
        }

        if ($holiday = IndonesiaHolidayCalendar::holiday($date)) {
            $fail("Tanggal pelaksanaan bertepatan dengan {$holiday['name']}.");
        }
    }
}
