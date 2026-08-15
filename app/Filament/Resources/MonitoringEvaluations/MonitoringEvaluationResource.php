<?php

namespace App\Filament\Resources\MonitoringEvaluations;

use App\Filament\Resources\MonitoringEvaluations\Pages\CreateMonitoringEvaluation;
use App\Filament\Resources\MonitoringEvaluations\Pages\EditMonitoringEvaluation;
use App\Filament\Resources\MonitoringEvaluations\Pages\ListMonitoringEvaluations;
use App\Filament\Resources\MonitoringEvaluations\Pages\ViewMonitoringEvaluation;
use App\Filament\Resources\MonitoringEvaluations\Schemas\MonitoringEvaluationForm;
use App\Filament\Resources\MonitoringEvaluations\Schemas\MonitoringEvaluationInfolist;
use App\Filament\Resources\MonitoringEvaluations\Tables\MonitoringEvaluationsTable;
use App\Models\MonitoringEvaluation;
use App\Support\SibatigMetrics;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use UnitEnum;

class MonitoringEvaluationResource extends Resource
{
    protected static ?string $model = MonitoringEvaluation::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedPresentationChartLine;

    protected static ?string $navigationLabel = 'Monitoring & Evaluasi';

    protected static ?string $modelLabel = 'monitoring dan evaluasi';

    protected static ?string $pluralModelLabel = 'Monitoring & Evaluasi';

    protected static string|UnitEnum|null $navigationGroup = 'Menu Utama';

    protected static ?int $navigationSort = 30;

    public static function getGloballySearchableAttributes(): array
    {
        return ['pkptActivity.assignment', 'stage', 'achievement', 'obstacles', 'follow_up'];
    }

    public static function getNavigationBadge(): ?string
    {
        return (string) SibatigMetrics::get('monitoring_unique');
    }

    public static function form(Schema $schema): Schema
    {
        return MonitoringEvaluationForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return MonitoringEvaluationInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return MonitoringEvaluationsTable::configure($table);
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
            'index' => ListMonitoringEvaluations::route('/'),
            'create' => CreateMonitoringEvaluation::route('/create'),
            'view' => ViewMonitoringEvaluation::route('/{record}'),
            'edit' => EditMonitoringEvaluation::route('/{record}/edit'),
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
