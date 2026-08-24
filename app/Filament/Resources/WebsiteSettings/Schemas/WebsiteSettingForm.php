<?php

namespace App\Filament\Resources\WebsiteSettings\Schemas;

use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;

class WebsiteSettingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Identitas website')
                    ->columns(2)
                    ->schema([
                        TextInput::make('site_name')->label('Nama aplikasi')->required()->maxLength(100),
                        TextInput::make('site_tagline')->label('Tagline')->maxLength(255),
                        TextInput::make('organization_name')->label('Nama organisasi')->required()->maxLength(255)->columnSpanFull(),
                        Textarea::make('description')->label('Deskripsi')->rows(4)->columnSpanFull(),
                        Placeholder::make('logo_preview')
                            ->label('Logo aktif')
                            ->content(fn (): \Illuminate\Support\HtmlString => new \Illuminate\Support\HtmlString(
                                '<img src="'.e(asset('images/logo-irban-3.jpg').'?v=20260819').'" alt="Logo Irban 3" width="132" height="132" style="width:132px;height:132px;object-fit:contain;border-radius:16px;border:1px solid #e5e7eb;background:#fff">',
                            ))
                            ->helperText('Logo Irban 3 digunakan secara konsisten pada seluruh website.')
                            ->columnSpanFull(),
                    ]),
                Section::make('Tampilan dan regional')
                    ->columns(3)
                    ->schema([
                        Select::make('theme_preset')
                            ->label('Preset tema')
                            ->options([
                                'ocean' => 'Ocean Blue',
                                'emerald' => 'Emerald',
                                'violet' => 'Modern Violet',
                                'ruby' => 'Ruby',
                            ])
                            ->default('ocean')
                            ->native(false)
                            ->live()
                            ->afterStateUpdated(function (?string $state, Set $set): void {
                                $colors = match ($state) {
                                    'emerald' => ['#0f766e', '#f59e0b', '#063a36', '#f2f8f6'],
                                    'violet' => ['#6d4aff', '#ec4899', '#25124a', '#f7f5fc'],
                                    'ruby' => ['#dc2626', '#f59e0b', '#450a0a', '#fff7f7'],
                                    default => ['#1769d2', '#f3b73f', '#061b3b', '#f4f7fb'],
                                };

                                $set('primary_color', $colors[0]);
                                $set('accent_color', $colors[1]);
                                $set('sidebar_color', $colors[2]);
                                $set('canvas_color', $colors[3]);
                            })
                            ->columnSpanFull(),
                        ColorPicker::make('primary_color')->label('Warna utama')->default('#1769d2')->required(),
                        ColorPicker::make('accent_color')->label('Warna aksen')->default('#f3b73f')->required(),
                        ColorPicker::make('sidebar_color')->label('Warna sidebar')->default('#061b3b')->required(),
                        ColorPicker::make('canvas_color')->label('Warna latar')->default('#f4f7fb')->required(),
                        TextInput::make('active_year')->label('Tahun aktif')->numeric()->minValue(2020)->maxValue(2100)->default(2026)->required(),
                        Select::make('timezone')->label('Zona waktu')->options(['Asia/Jakarta' => 'Asia/Jakarta (WIB)'])->default('Asia/Jakarta')->required()->native(false),
                        Select::make('locale')->label('Bahasa')->options(['id' => 'Bahasa Indonesia', 'en' => 'English'])->default('id')->required()->native(false),
                        Toggle::make('maintenance_mode')->label('Mode pemeliharaan')->helperText('Hanya sebagai konfigurasi aplikasi; tidak menjalankan artisan down.'),
                    ]),
                Section::make('Kontak')
                    ->columns(2)
                    ->schema([
                        TextInput::make('contact_email')->label('Email')->email()->maxLength(255),
                        TextInput::make('contact_phone')->label('Telepon')->tel()->maxLength(30),
                        Textarea::make('address')->label('Alamat')->rows(3)->columnSpanFull(),
                    ]),
            ]);
    }
}
