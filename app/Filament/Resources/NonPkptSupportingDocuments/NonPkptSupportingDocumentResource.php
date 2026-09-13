<?php

namespace App\Filament\Resources\NonPkptSupportingDocuments;

use App\Filament\Resources\NonPkptSupportingDocuments\Pages\CreateNonPkptSupportingDocument;
use App\Filament\Resources\NonPkptSupportingDocuments\Pages\EditNonPkptSupportingDocument;
use App\Filament\Resources\NonPkptSupportingDocuments\Pages\ListNonPkptSupportingDocuments;
use App\Filament\Resources\NonPkptSupportingDocuments\Pages\ViewNonPkptSupportingDocument;
use App\Filament\Support\AssignmentDocumentForm;
use App\Filament\Support\AssignmentDocumentInfolist;
use App\Filament\Support\AssignmentDocumentTable;
use App\Models\SupportingDocument;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use UnitEnum;

class NonPkptSupportingDocumentResource extends Resource
{
    protected static ?string $model = SupportingDocument::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedPaperClip;

    protected static ?string $navigationLabel = 'Dokumen Pendukung';

    protected static ?string $modelLabel = 'dokumen pendukung Non-PKPT';

    protected static ?string $pluralModelLabel = 'Dokumen Pendukung Non-PKPT';

    protected static ?string $recordTitleAttribute = 'title';

    protected static string|UnitEnum|null $navigationGroup = 'Non-PKPT';

    protected static ?int $navigationSort = 60;

    public static function getNavigationBadge(): ?string
    {
        return (string) static::getEloquentQuery()->count();
    }

    public static function form(Schema $schema): Schema
    {
        return AssignmentDocumentForm::supportingDocument($schema, 'NON PKPT');
    }

    public static function infolist(Schema $schema): Schema
    {
        return AssignmentDocumentInfolist::supportingDocument($schema);
    }

    public static function table(Table $table): Table
    {
        return AssignmentDocumentTable::supportingDocument($table, 'NON PKPT');
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
            'index' => ListNonPkptSupportingDocuments::route('/'),
            'create' => CreateNonPkptSupportingDocument::route('/create'),
            'view' => ViewNonPkptSupportingDocument::route('/{record}'),
            'edit' => EditNonPkptSupportingDocument::route('/{record}/edit'),
        ];
    }
}
