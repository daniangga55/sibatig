<?php

namespace App\Filament\Resources\MonitoringEvaluations\Schemas;

use App\Enums\PkptStatus;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class MonitoringEvaluationInfolist
{
    public static function configure(Schema $schema, string $scope = 'PKPT'): Schema
    {
        $relationship = $scope === 'PKPT' ? 'pkptActivity' : 'nonPkptActivity';

        return $schema->components([
            Section::make('Ringkasan monitoring')
                ->columns(3)
                ->schema([
                    TextEntry::make($relationship.'.source_number')->label("No. {$scope}")->badge(),
                    TextEntry::make('evaluation_date')->label('Tanggal evaluasi')->date('d M Y'),
                    TextEntry::make('stage')->label('Tahapan')->placeholder('—'),
                    TextEntry::make($relationship.'.assignment')->label('Kegiatan')->columnSpanFull(),
                    TextEntry::make('status')->label('Status')->badge()->formatStateUsing(fn (PkptStatus $state): string => $state->label())->color(fn (PkptStatus $state): string => $state->color()),
                    TextEntry::make('progress')->label('Progres')->suffix('%'),
                    TextEntry::make('updater.name')->label('Diperbarui oleh')->placeholder('Sistem'),
                ]),
            Section::make('Hasil evaluasi')
                ->columns(2)
                ->schema([
                    TextEntry::make('actual_start')->label('Realisasi mulai')->date('d M Y')->placeholder('—'),
                    TextEntry::make('actual_end')->label('Realisasi selesai')->date('d M Y')->placeholder('—'),
                    TextEntry::make('achievement')->label('Capaian')->placeholder('—')->columnSpanFull(),
                    TextEntry::make('obstacles')->label('Kendala')->placeholder('Tidak ada kendala')->columnSpanFull(),
                    TextEntry::make('follow_up')->label('Tindak lanjut')->placeholder('—')->columnSpanFull(),
                ]),
        ]);
    }
}
