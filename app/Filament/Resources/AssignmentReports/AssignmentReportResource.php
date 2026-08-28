<?php

namespace App\Filament\Resources\AssignmentReports;

use App\Filament\Resources\AssignmentReports\Pages\CreateAssignmentReport;
use App\Filament\Resources\AssignmentReports\Pages\EditAssignmentReport;
use App\Filament\Resources\AssignmentReports\Pages\ListAssignmentReports;
use App\Filament\Resources\AssignmentReports\Pages\ViewAssignmentReport;
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

class AssignmentReportResource extends Resource
{
    protected static ?string $model = AssignmentReport::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentCheck;

    protected static ?string $navigationLabel = 'Laporan Hasil Penugasan';

    protected static ?string $modelLabel = 'laporan hasil penugasan PKPT';

    protected static ?string $pluralModelLabel = 'Laporan Hasil Penugasan PKPT';

    protected static ?string $recordTitleAttribute = 'title';

    protected static string|UnitEnum|null $navigationGroup = 'PKPT';

    protected static ?int $navigationSort = 50;

    public static function getNavigationBadge(): ?string
    {
        return (string) static::getEloquentQuery()->count();
    }

    public static function form(Schema $schema): Schema
    {
        return AssignmentDocumentForm::assignmentReport($schema, 'PKPT');
    }

    public static function infolist(Schema $schema): Schema
    {
        return AssignmentDocumentInfolist::assignmentReport($schema);
    }

    public static function table(Table $table): Table
    {
        return AssignmentDocumentTable::assignmentReport($table, 'PKPT');
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->whereHas('sptRecord', fn (Builder $query) => $query->where('relation_type', 'PKPT'));
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return static::getEloquentQuery()->withoutGlobalScopes([SoftDeletingScope::class]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListAssignmentReports::route('/'),
            'create' => CreateAssignmentReport::route('/create'),
            'view' => ViewAssignmentReport::route('/{record}'),
            'edit' => EditAssignmentReport::route('/{record}/edit'),
        ];
    }
}
