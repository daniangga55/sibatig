<?php

namespace App\Filament\Resources\WorkPapers;

use App\Filament\Resources\WorkPapers\Pages\CreateWorkPaper;
use App\Filament\Resources\WorkPapers\Pages\EditWorkPaper;
use App\Filament\Resources\WorkPapers\Pages\ListWorkPapers;
use App\Filament\Resources\WorkPapers\Pages\ViewWorkPaper;
use App\Filament\Support\AssignmentDocumentForm;
use App\Filament\Support\AssignmentDocumentInfolist;
use App\Filament\Support\AssignmentDocumentTable;
use App\Models\WorkPaper;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use UnitEnum;

class WorkPaperResource extends Resource
{
    protected static ?string $model = WorkPaper::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentDuplicate;

    protected static ?string $navigationLabel = 'Kertas Kerja';

    protected static ?string $modelLabel = 'kertas kerja PKPT';

    protected static ?string $pluralModelLabel = 'Kertas Kerja PKPT';

    protected static ?string $recordTitleAttribute = 'title';

    protected static string|UnitEnum|null $navigationGroup = 'PKPT';

    protected static ?int $navigationSort = 40;

    public static function getNavigationBadge(): ?string
    {
        return (string) static::getEloquentQuery()->count();
    }

    public static function form(Schema $schema): Schema
    {
        return AssignmentDocumentForm::workPaper($schema, 'PKPT');
    }

    public static function infolist(Schema $schema): Schema
    {
        return AssignmentDocumentInfolist::workPaper($schema);
    }

    public static function table(Table $table): Table
    {
        return AssignmentDocumentTable::workPaper($table, 'PKPT');
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
            'index' => ListWorkPapers::route('/'),
            'create' => CreateWorkPaper::route('/create'),
            'view' => ViewWorkPaper::route('/{record}'),
            'edit' => EditWorkPaper::route('/{record}/edit'),
        ];
    }
}
