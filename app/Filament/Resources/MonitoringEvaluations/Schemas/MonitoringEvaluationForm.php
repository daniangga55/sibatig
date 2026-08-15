<?php

namespace App\Filament\Resources\MonitoringEvaluations\Schemas;

use App\Enums\PkptStatus;
use App\Models\PkptActivity;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class MonitoringEvaluationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Referensi kegiatan')
                    ->description('Satu kegiatan dapat memiliki beberapa riwayat monitoring.')
                    ->columns(2)
                    ->schema([
                        Select::make('pkpt_activity_id')
                            ->label('Kegiatan PKPT')
                            ->relationship('pkptActivity', 'assignment', modifyQueryUsing: fn ($query) => $query->where('year', 2026)->orderBy('source_number'))
                            ->getOptionLabelFromRecordUsing(fn (PkptActivity $record): string => "#{$record->source_number} · {$record->assignment}")
                            ->searchable(['assignment', 'source_number'])
                            ->preload()
                            ->default(fn (): ?int => request()->integer('pkpt_activity_id') ?: null)
                            ->required()
                            ->columnSpanFull(),
                        DatePicker::make('evaluation_date')->label('Tanggal evaluasi')->default(now())->native(false)->displayFormat('d/m/Y')->required(),
                        TextInput::make('stage')->label('Tahapan kegiatan')->placeholder('Contoh: Penyusunan laporan')->maxLength(255),
                    ]),
                Section::make('Capaian pelaksanaan')
                    ->columns(2)
                    ->schema([
                        Select::make('status')->label('Status')->options(PkptStatus::options())->required()->native(false),
                        TextInput::make('progress')->label('Progres')->numeric()->suffix('%')->minValue(0)->maxValue(100)->required(),
                        DatePicker::make('actual_start')->label('Realisasi mulai')->native(false)->displayFormat('d/m/Y'),
                        DatePicker::make('actual_end')->label('Realisasi selesai')->native(false)->displayFormat('d/m/Y')->afterOrEqual('actual_start'),
                        Textarea::make('achievement')->label('Capaian')->rows(4)->columnSpanFull(),
                        Textarea::make('obstacles')->label('Kendala')->rows(4)->columnSpanFull(),
                        Textarea::make('follow_up')->label('Rencana tindak lanjut')->rows(4)->columnSpanFull(),
                    ]),
            ]);
    }
}
