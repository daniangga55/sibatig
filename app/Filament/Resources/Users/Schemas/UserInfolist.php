<?php

namespace App\Filament\Resources\Users\Schemas;

use App\Enums\UserRole;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class UserInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Detail pengguna')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('name')->label('Nama'),
                        TextEntry::make('email')->label('Email')->copyable(),
                        TextEntry::make('phone')->label('Telepon')->placeholder('—'),
                        TextEntry::make('role')->label('Peran')->badge()->formatStateUsing(fn (UserRole $state): string => $state->label()),
                        IconEntry::make('is_active')->label('Aktif')->boolean(),
                        TextEntry::make('last_login_at')->label('Login terakhir')->dateTime('d M Y H:i')->placeholder('Belum pernah login'),
                        TextEntry::make('created_at')->label('Dibuat')->dateTime('d M Y H:i'),
                        TextEntry::make('updated_at')->label('Diperbarui')->dateTime('d M Y H:i'),
                    ]),
            ]);
    }
}
