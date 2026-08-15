<?php

namespace App\Filament\Resources\WebsiteSettings;

use App\Filament\Resources\WebsiteSettings\Pages\CreateWebsiteSetting;
use App\Filament\Resources\WebsiteSettings\Pages\EditWebsiteSetting;
use App\Filament\Resources\WebsiteSettings\Pages\ListWebsiteSettings;
use App\Filament\Resources\WebsiteSettings\Pages\ViewWebsiteSetting;
use App\Filament\Resources\WebsiteSettings\Schemas\WebsiteSettingForm;
use App\Filament\Resources\WebsiteSettings\Schemas\WebsiteSettingInfolist;
use App\Filament\Resources\WebsiteSettings\Tables\WebsiteSettingsTable;
use App\Models\WebsiteSetting;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class WebsiteSettingResource extends Resource
{
    protected static ?string $model = WebsiteSetting::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCog6Tooth;

    protected static ?string $navigationLabel = 'Pengaturan Website';

    protected static ?string $modelLabel = 'pengaturan website';

    protected static ?string $pluralModelLabel = 'Pengaturan Website';

    protected static ?string $recordTitleAttribute = 'site_name';

    protected static string|UnitEnum|null $navigationGroup = 'Administrasi';

    protected static ?int $navigationSort = 20;

    public static function form(Schema $schema): Schema
    {
        return WebsiteSettingForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return WebsiteSettingInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return WebsiteSettingsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListWebsiteSettings::route('/'),
            'create' => CreateWebsiteSetting::route('/create'),
            'view' => ViewWebsiteSetting::route('/{record}'),
            'edit' => EditWebsiteSetting::route('/{record}/edit'),
        ];
    }
}
