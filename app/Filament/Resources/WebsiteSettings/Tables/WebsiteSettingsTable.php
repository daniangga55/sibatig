<?php

namespace App\Filament\Resources\WebsiteSettings\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\ColorColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class WebsiteSettingsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('site_name')->label('Nama aplikasi')->searchable()->weight('medium'),
                TextColumn::make('organization_name')->label('Organisasi')->searchable()->wrap(),
                TextColumn::make('active_year')->label('Tahun aktif')->badge()->sortable(),
                ColorColumn::make('primary_color')->label('Warna utama'),
                IconColumn::make('maintenance_mode')->label('Pemeliharaan')->boolean(),
                TextColumn::make('updated_at')->label('Diperbarui')->dateTime('d M Y H:i')->sortable(),
            ])
            ->defaultSort('updated_at', 'desc')
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
