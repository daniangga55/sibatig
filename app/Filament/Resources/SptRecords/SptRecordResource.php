<?php

namespace App\Filament\Resources\SptRecords;

use App\Filament\Resources\SptRecords\Pages\CreateSptRecord;
use App\Filament\Resources\SptRecords\Pages\EditSptRecord;
use App\Filament\Resources\SptRecords\Pages\ListSptRecords;
use App\Filament\Resources\SptRecords\Pages\ViewSptRecord;
use App\Filament\Resources\SptRecords\Schemas\SptRecordForm;
use App\Filament\Resources\SptRecords\Schemas\SptRecordInfolist;
use App\Filament\Resources\SptRecords\Tables\SptRecordsTable;
use App\Models\SptRecord;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use UnitEnum;

class SptRecordResource extends Resource
{
    protected static ?string $model = SptRecord::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentText;

    protected static ?string $navigationLabel = 'Surat Perintah Tugas';

    protected static ?string $modelLabel = 'SPT';

    protected static ?string $pluralModelLabel = 'Surat Perintah Tugas PKPT';

    protected static ?string $recordTitleAttribute = 'document_number';

    protected static string|UnitEnum|null $navigationGroup = 'PKPT';

    protected static ?int $navigationSort = 30;

    public static function getGloballySearchableAttributes(): array
    {
        return ['document_number', 'subject', 'audit_object', 'report_number'];
    }

    public static function getNavigationBadge(): ?string
    {
        return (string) static::getEloquentQuery()->count();
    }

    public static function form(Schema $schema): Schema
    {
        return SptRecordForm::configure($schema, 'PKPT');
    }

    public static function infolist(Schema $schema): Schema
    {
        return SptRecordInfolist::configure($schema, 'PKPT');
    }

    public static function table(Table $table): Table
    {
        return SptRecordsTable::configure($table, 'PKPT');
    }

    public static function getPages(): array
    {
        return [
            'index' => ListSptRecords::route('/'),
            'create' => CreateSptRecord::route('/create'),
            'view' => ViewSptRecord::route('/{record}'),
            'edit' => EditSptRecord::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->where('relation_type', 'PKPT')
            ->withoutGlobalScopes([SoftDeletingScope::class]);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('relation_type', 'PKPT');
    }
}
