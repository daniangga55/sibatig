<?php

namespace App\Models\Concerns;

use App\Support\SibatigMetrics;

trait FlushesSibatigMetrics
{
    public static function bootFlushesSibatigMetrics(): void
    {
        static::saved(fn () => SibatigMetrics::forget());
        static::deleted(fn () => SibatigMetrics::forget());

        if (method_exists(static::class, 'restored')) {
            static::restored(fn () => SibatigMetrics::forget());
        }
    }
}
