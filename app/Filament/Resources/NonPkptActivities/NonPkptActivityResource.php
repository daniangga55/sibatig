<?php

namespace App\Filament\Resources\NonPkptActivities;

use App\Filament\Resources\NonPkptActivities\Pages\CreateNonPkptActivity;
use App\Filament\Resources\NonPkptActivities\Pages\EditNonPkptActivity;
use App\Filament\Resources\NonPkptActivities\Pages\ListNonPkptActivities;
use App\Filament\Resources\NonPkptActivities\Pages\ViewNonPkptActivity;
use App\Filament\Resources\PkptActivities\Schemas\PkptActivityForm;
use App\Filament\Resources\PkptActivities\Schemas\PkptActivityInfolist;
use App\Filament\Resources\PkptActivities\Tables\PkptActivitiesTable;
use App\Models\NonPkptActivity;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use UnitEnum;

class NonPkptActivityResource extends Resource
{
    protected static ?string $model = NonPkptActivity::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentList;

    protected static ?string $navigationLabel = 'Data Non-PKPT';

    protected static ?string $modelLabel = 'kegiatan Non-PKPT';

    protected static ?string $pluralModelLabel = 'Data Non-PKPT';

    protected static ?string $recordTitleAttribute = 'assignment';

    protected static string|UnitEnum|null $navigationGroup = 'Non-PKPT';

    protected static ?int $navigationSort = 10;

    public static function getGloballySearchableAttributes(): array
    {
        return ['assignment', 'assignment_type', 'audit_object', 'executor'];
    }

    public static function getNavigationBadge(): ?string
    {
        return (string) NonPkptActivity::query()->count();
    }

    public static function form(Schema $schema): Schema
    {
        return PkptActivityForm::configure($schema, 'Non-PKPT');
    }

    public static function infolist(Schema $schema): Schema
    {
        return PkptActivityInfolist::configure($schema, 'Non-PKPT');
    }

    public static function table(Table $table): Table
    {
        return PkptActivitiesTable::configure($table, 'monitoringEvaluations', 'Non-PKPT');
    }

    public static function getPages(): array
    {
        return [
            'index' => ListNonPkptActivities::route('/'),
            'create' => CreateNonPkptActivity::route('/create'),
            'view' => ViewNonPkptActivity::route('/{record}'),
            'edit' => EditNonPkptActivity::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([SoftDeletingScope::class]);
    }
}
