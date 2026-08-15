<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['site_name', 'site_tagline', 'organization_name', 'description', 'theme_preset', 'primary_color', 'accent_color', 'sidebar_color', 'canvas_color', 'logo_path', 'contact_email', 'contact_phone', 'address', 'timezone', 'locale', 'active_year', 'maintenance_mode'])]
class WebsiteSetting extends Model
{
    use HasFactory;

    private static ?self $runtimeCurrent = null;

    private static bool $runtimeCurrentResolved = false;

    protected function casts(): array
    {
        return [
            'active_year' => 'integer',
            'maintenance_mode' => 'boolean',
        ];
    }

    public static function current(): ?self
    {
        if (self::$runtimeCurrentResolved) {
            return self::$runtimeCurrent;
        }

        $cacheKey = 'website-settings:v2';
        $attributes = cache()->remember(
            $cacheKey,
            now()->addMinutes(10),
            fn (): ?array => self::query()->first()?->getAttributes(),
        );

        if (! is_array($attributes)) {
            cache()->forget($cacheKey);

            $attributes = self::query()->first()?->getAttributes();

            cache()->put($cacheKey, $attributes, now()->addMinutes(10));
        }

        self::$runtimeCurrentResolved = true;

        return self::$runtimeCurrent = $attributes ? (new self)->newFromBuilder($attributes) : null;
    }

    public static function themeColor(string $field, string $fallback): string
    {
        $color = rescue(fn (): ?string => self::current()?->{$field}, null, report: false);

        return is_string($color) && preg_match('/^#[0-9a-fA-F]{6}$/', $color) ? $color : $fallback;
    }

    protected static function booted(): void
    {
        static::saved(function (): void {
            self::flushCurrentCache();
        });
        static::deleted(function (): void {
            self::flushCurrentCache();
        });
    }

    private static function flushCurrentCache(): void
    {
        self::$runtimeCurrent = null;
        self::$runtimeCurrentResolved = false;
        cache()->forget('website-settings');
        cache()->forget('website-settings:v2');
    }
}
