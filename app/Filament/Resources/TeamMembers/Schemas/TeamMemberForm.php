<?php

namespace App\Filament\Resources\TeamMembers\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class TeamMemberForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Identitas anggota')
                    ->description('Data kepegawaian anggota Tim Irban 3.')
                    ->columns(2)
                    ->schema([
                        TextInput::make('full_name')->label('Nama lengkap beserta gelar')->required()->maxLength(255)->columnSpanFull(),
                        TextInput::make('nip')->label('NIP')->required()->unique(ignoreRecord: true)->maxLength(32)->helperText('Masukkan angka tanpa spasi.'),
                        TextInput::make('position')->label('Jabatan')->required()->maxLength(255),
                        TextInput::make('rank')->label('Pangkat')->maxLength(255),
                        TextInput::make('grade')->label('Golongan')->maxLength(10),
                        TextInput::make('email')->label('Email')->email()->maxLength(255),
                        TextInput::make('phone')->label('Nomor telepon')->tel()->maxLength(30),
                    ]),
                Section::make('Akses dan status')
                    ->columns(2)
                    ->schema([
                        Select::make('user_id')->label('Akun terkait')->relationship('user', 'name')->searchable()->preload()->placeholder('Tidak terhubung ke akun'),
                        TextInput::make('sort_order')->label('Urutan tampil')->numeric()->minValue(0)->default(0)->required(),
                        Toggle::make('is_leader')->label('Pimpinan tim'),
                        Toggle::make('is_active')->label('Anggota aktif')->default(true),
                        Textarea::make('notes')->label('Catatan')->rows(4)->columnSpanFull(),
                    ]),
            ]);
    }
}
