<?php

namespace App\Filament\Resources\NonPkptMonitoringEvaluations;

use App\Enums\PkptStatus;
use App\Filament\Resources\MonitoringEvaluations\Schemas\MonitoringEvaluationForm;
use App\Filament\Resources\MonitoringEvaluations\Schemas\MonitoringEvaluationInfolist;
use App\Filament\Resources\MonitoringEvaluations\Tables\MonitoringEvaluationsTable;
use App\Filament\Resources\NonPkptMonitoringEvaluations\Pages\CreateNonPkptMonitoringEvaluation;
use App\Filament\Resources\NonPkptMonitoringEvaluations\Pages\EditNonPkptMonitoringEvaluation;
use App\Filament\Resources\NonPkptMonitoringEvaluations\Pages\ListNonPkptMonitoringEvaluations;
use App\Filament\Resources\NonPkptMonitoringEvaluations\Pages\ViewNonPkptMonitoringEvaluation;
use App\Models\MonitoringEvaluation;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use UnitEnum;

class NonPkptMonitoringEvaluationResource extends Resource
{
    protected static ?string $model = MonitoringEvaluation::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedPresentationChartLine;

    protected static ?string $navigationLabel = 'Monitoring & Evaluasi';

    protected static ?string $modelLabel = 'monitoring Non-PKPT';

    protected static ?string $pluralModelLabel = 'Monitoring & Evaluasi Non-PKPT';

    protected static string|UnitEnum|null $navigationGroup = 'Non-PKPT';

    protected static ?int $navigationSort = 20;

    public static function getGloballySearchableAttributes(): array
    {
        return ['nonPkptActivity.assignment', 'stage', 'achievement', 'obstacles', 'follow_up'];
    }

    public static function getNavigationBadge(): ?string
    {
        return (string) static::getEloquentQuery()->count();
    }

    public static function form(Schema $schema): Schema
    {
        return MonitoringEvaluationForm::configure($schema, 'Non-PKPT');
    }

    public static function infolist(Schema $schema): Schema
    {
        return MonitoringEvaluationInfolist::configure($schema, 'Non-PKPT');
    }

    public static function table(Table $table): Table
    {
        return MonitoringEvaluationsTable::configure($table, 'Non-PKPT');
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->whereNotNull('non_pkpt_activity_id')
            ->where('status', PkptStatus::Selesai->value);
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->whereNotNull('non_pkpt_activity_id')
            ->where('status', PkptStatus::Selesai->value)
            ->withoutGlobalScopes([SoftDeletingScope::class]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListNonPkptMonitoringEvaluations::route('/'),
            'create' => CreateNonPkptMonitoringEvaluation::route('/create'),
            'view' => ViewNonPkptMonitoringEvaluation::route('/{record}'),
            'edit' => EditNonPkptMonitoringEvaluation::route('/{record}/edit'),
        ];
    }
}
