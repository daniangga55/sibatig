<?php

namespace App\Enums;

enum UserRole: string
{
    case SuperAdmin = 'super_admin';
    case Admin = 'admin';
    case Pimpinan = 'pimpinan';
    case Auditor = 'auditor';
    case Viewer = 'viewer';

    public function label(): string
    {
        return match ($this) {
            self::SuperAdmin => 'Super Admin',
            self::Admin => 'Admin',
            self::Pimpinan => 'Pimpinan',
            self::Auditor => 'Auditor',
            self::Viewer => 'Viewer',
        };
    }

    public static function options(): array
    {
        return collect(self::cases())->mapWithKeys(fn (self $role): array => [$role->value => $role->label()])->all();
    }
}
