<?php

namespace App\Enums;

enum PkptCategory: string
{
    case Audit = 'audit';
    case Reviu = 'reviu';
    case Monitoring = 'monitoring';
    case Evaluasi = 'evaluasi';
    case Pendampingan = 'pendampingan';
    case Mandatory = 'mandatory';

    public function label(): string
    {
        return ucfirst($this->value);
    }

    public static function options(): array
    {
        return collect(self::cases())->mapWithKeys(fn (self $category): array => [$category->value => $category->label()])->all();
    }
}
