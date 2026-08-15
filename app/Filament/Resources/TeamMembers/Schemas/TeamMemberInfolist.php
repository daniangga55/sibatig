<?php

namespace App\Filament\Resources\TeamMembers\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class TeamMemberInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Profil anggota')
                    ->columns(3)
                    ->schema([
                        TextEntry::make('full_name')->label('Nama')->columnSpan(2),
                        TextEntry::make('nip')->label('NIP')->copyable(),
                        TextEntry::make('position')->label('Jabatan'),
                        TextEntry::make('rank')->label('Pangkat')->placeholder('—'),
                        TextEntry::make('grade')->label('Golongan')->placeholder('—'),
                        TextEntry::make('email')->label('Email')->placeholder('—'),
                        TextEntry::make('phone')->label('Telepon')->placeholder('—'),
                        TextEntry::make('user.name')->label('Akun terkait')->placeholder('Tidak terhubung'),
                        IconEntry::make('is_leader')->label('Pimpinan')->boolean(),
                        IconEntry::make('is_active')->label('Aktif')->boolean(),
                        TextEntry::make('notes')->label('Catatan')->placeholder('—')->columnSpanFull(),
                    ]),
            ]);
    }
}
