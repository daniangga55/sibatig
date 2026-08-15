<?php

namespace App\Filament\Resources\PkptActivities\Schemas;

use App\Enums\PkptCategory;
use App\Enums\PkptStatus;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Illuminate\Validation\Rules\Unique;

class PkptActivityForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Identitas kegiatan PKPT')
                    ->description('Nomor mengacu pada sumber PKPT resmi tahun berjalan.')
                    ->columns(3)
                    ->schema([
                        TextInput::make('year')->label('Tahun')->numeric()->minValue(2020)->maxValue(2100)->default(2026)->required(),
                        TextInput::make('source_number')
                            ->label('Nomor PKPT')->numeric()->minValue(1)->required()
                            ->unique(ignoreRecord: true, modifyRuleUsing: fn (Unique $rule, Get $get): Unique => $rule->where('year', $get('year'))),
                        Select::make('category')->label('Kategori')->options(PkptCategory::options())->required()->native(false),
                        TextInput::make('assignment_type')->label('Jenis penugasan')->required()->maxLength(255),
                        Textarea::make('assignment')->label('Uraian penugasan')->required()->rows(4)->columnSpan(2),
                        Textarea::make('audit_object')->label('Objek pemeriksaan (Obrik)')->rows(3)->columnSpan(2),
                        TextInput::make('executor')->label('Pelaksana/Pengampu')->default('IRBAN III')->required(),
                        TextInput::make('apip_count')->label('Jumlah APIP')->numeric()->minValue(0)->default(0)->required(),
                    ]),
                Section::make('Jadwal dan realisasi')
                    ->description('Status terkini juga akan diperbarui otomatis dari entri monitoring terbaru.')
                    ->columns(4)
                    ->schema([
                        DatePicker::make('planned_start')->label('Rencana mulai')->native(false)->displayFormat('d/m/Y'),
                        DatePicker::make('planned_end')->label('Rencana selesai')->native(false)->displayFormat('d/m/Y')->afterOrEqual('planned_start'),
                        DatePicker::make('actual_start')->label('Realisasi mulai')->native(false)->displayFormat('d/m/Y'),
                        DatePicker::make('actual_end')->label('Realisasi selesai')->native(false)->displayFormat('d/m/Y')->afterOrEqual('actual_start'),
                        Select::make('status')->label('Status')->options(PkptStatus::options())->default(PkptStatus::BelumDilaksanakan->value)->required()->native(false)->columnSpan(2),
                        TextInput::make('progress')->label('Progres')->numeric()->suffix('%')->minValue(0)->maxValue(100)->default(0)->required()->columnSpan(2),
                    ]),
                Section::make('Tim dan catatan')
                    ->columns(2)
                    ->schema([
                        Select::make('teamMembers')
                            ->label('Anggota pelaksana')->relationship('teamMembers', 'full_name')->multiple()->searchable()->preload()
                            ->helperText('Pilih satu atau lebih anggota Tim Irban 3.')->columnSpanFull(),
                        Textarea::make('notes')->label('Catatan')->rows(4)->columnSpanFull(),
                    ]),
            ]);
    }
}
