<?php

namespace App\Filament\Resources\WebsiteSettings\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class WebsiteSettingInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Profil website')
                    ->columns(3)
                    ->schema([
                        ImageEntry::make('logo_path')->label('Logo')->disk('public')->circular()->placeholder('Belum ada logo'),
                        TextEntry::make('site_name')->label('Nama aplikasi'),
                        TextEntry::make('site_tagline')->label('Tagline')->placeholder('—'),
                        TextEntry::make('organization_name')->label('Organisasi')->columnSpan(2),
                        TextEntry::make('active_year')->label('Tahun aktif')->badge(),
                        TextEntry::make('theme_preset')->label('Preset tema')->badge(),
                        TextEntry::make('description')->label('Deskripsi')->columnSpanFull(),
                        TextEntry::make('timezone')->label('Zona waktu'),
                        TextEntry::make('locale')->label('Bahasa'),
                        IconEntry::make('maintenance_mode')->label('Mode pemeliharaan')->boolean(),
                        TextEntry::make('contact_email')->label('Email')->placeholder('—'),
                        TextEntry::make('contact_phone')->label('Telepon')->placeholder('—'),
                        TextEntry::make('address')->label('Alamat')->placeholder('—')->columnSpanFull(),
                    ]),
            ]);
    }
}
