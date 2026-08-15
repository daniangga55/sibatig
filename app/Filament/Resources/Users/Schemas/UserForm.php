<?php

namespace App\Filament\Resources\Users\Schemas;

use App\Enums\UserRole;
use App\Models\User;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Identitas pengguna')
                    ->description('Akun yang dapat mengakses panel SIBATIG.')
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')->label('Nama lengkap')->required()->maxLength(255)->autofocus(),
                        TextInput::make('email')->label('Alamat email')->email()->required()->unique(ignoreRecord: true)->maxLength(255),
                        TextInput::make('phone')->label('Nomor telepon')->tel()->maxLength(30),
                        Select::make('role')->label('Peran')->options(UserRole::options())->default(UserRole::Viewer->value)->required()->native(false),
                        Toggle::make('is_active')->label('Akun aktif')->helperText('Akun nonaktif tidak dapat masuk ke panel.')->default(true),
                    ]),
                Section::make('Keamanan')
                    ->description('Kosongkan kata sandi saat mengedit jika tidak ingin mengubahnya.')
                    ->columns(2)
                    ->schema([
                        TextInput::make('password')
                            ->label('Kata sandi')->password()->revealable()
                            ->required(fn (?User $record): bool => $record === null)
                            ->dehydrated(fn (?string $state): bool => filled($state))
                            ->minLength(8)->same('password_confirmation'),
                        TextInput::make('password_confirmation')->label('Konfirmasi kata sandi')->password()->revealable()->dehydrated(false),
                    ]),
            ]);
    }
}
