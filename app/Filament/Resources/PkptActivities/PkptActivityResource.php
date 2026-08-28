<?php

namespace App\Filament\Resources\PkptActivities;

use App\Filament\Resources\PkptActivities\Pages\CreatePkptActivity;
use App\Filament\Resources\PkptActivities\Pages\EditPkptActivity;
use App\Filament\Resources\PkptActivities\Pages\ListPkptActivities;
use App\Filament\Resources\PkptActivities\Pages\ViewPkptActivity;
use App\Filament\Resources\PkptActivities\Schemas\PkptActivityForm;
use App\Filament\Resources\PkptActivities\Schemas\PkptActivityInfolist;
use App\Filament\Resources\PkptActivities\Tables\PkptActivitiesTable;
use App\Models\PkptActivity;
use App\Support\SibatigMetrics;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use UnitEnum;

class PkptActivityResource extends Resource
{
    protected static ?string $model = PkptActivity::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentCheck;

    protected static ?string $navigationLabel = 'Data PKPT';

    protected static ?string $modelLabel = 'kegiatan PKPT';

    protected static ?string $pluralModelLabel = 'Data PKPT';

    protected static ?string $recordTitleAttribute = 'assignment';

    protected static string|UnitEnum|null $navigationGroup = 'PKPT';

    protected static ?int $navigationSort = 10;

    public static function getGloballySearchableAttributes(): array
    {
        return ['assignment', 'assignment_type', 'audit_object', 'executor'];
    }

    public static function getNavigationBadge(): ?string
    {
        return (string) SibatigMetrics::get('pkpt_total');
    }

    public static function form(Schema $schema): Schema
    {
        return PkptActivityForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return PkptActivityInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PkptActivitiesTable::configure($table);
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
            'index' => ListPkptActivities::route('/'),
            'create' => CreatePkptActivity::route('/create'),
            'view' => ViewPkptActivity::route('/{record}'),
            'edit' => EditPkptActivity::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
