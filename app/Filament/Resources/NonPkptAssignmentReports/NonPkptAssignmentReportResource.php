<?php

namespace App\Filament\Resources\NonPkptAssignmentReports;

use App\Filament\Resources\NonPkptAssignmentReports\Pages\CreateNonPkptAssignmentReport;
use App\Filament\Resources\NonPkptAssignmentReports\Pages\EditNonPkptAssignmentReport;
use App\Filament\Resources\NonPkptAssignmentReports\Pages\ListNonPkptAssignmentReports;
use App\Filament\Resources\NonPkptAssignmentReports\Pages\ViewNonPkptAssignmentReport;
use App\Filament\Support\AssignmentDocumentForm;
use App\Filament\Support\AssignmentDocumentInfolist;
use App\Filament\Support\AssignmentDocumentTable;
use App\Models\AssignmentReport;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use UnitEnum;

class NonPkptAssignmentReportResource extends Resource
{
    protected static ?string $model = AssignmentReport::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentCheck;

    protected static ?string $navigationLabel = 'Laporan Hasil Penugasan';

    protected static ?string $modelLabel = 'laporan hasil penugasan Non-PKPT';

    protected static ?string $pluralModelLabel = 'Laporan Hasil Penugasan Non-PKPT';

    protected static ?string $recordTitleAttribute = 'title';

    protected static string|UnitEnum|null $navigationGroup = 'Non-PKPT';

    protected static ?int $navigationSort = 50;

    public static function getNavigationBadge(): ?string
    {
        return (string) static::getEloquentQuery()->count();
    }

    public static function form(Schema $schema): Schema
    {
        return AssignmentDocumentForm::assignmentReport($schema, 'NON PKPT');
    }

    public static function infolist(Schema $schema): Schema
    {
        return AssignmentDocumentInfolist::assignmentReport($schema);
    }

    public static function table(Table $table): Table
    {
        return AssignmentDocumentTable::assignmentReport($table, 'NON PKPT');
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->whereHas('sptRecord', fn (Builder $query) => $query->where('relation_type', 'NON PKPT'));
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return static::getEloquentQuery()->withoutGlobalScopes([SoftDeletingScope::class]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListNonPkptAssignmentReports::route('/'),
            'create' => CreateNonPkptAssignmentReport::route('/create'),
            'view' => ViewNonPkptAssignmentReport::route('/{record}'),
            'edit' => EditNonPkptAssignmentReport::route('/{record}/edit'),
        ];
    }
}
