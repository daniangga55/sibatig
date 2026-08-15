<?php

namespace App\Filament\Resources\PkptActivities\Schemas;

use App\Enums\PkptCategory;
use App\Enums\PkptStatus;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PkptActivityInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Kegiatan PKPT')
                    ->columns(3)
                    ->schema([
                        TextEntry::make('source_number')->label('Nomor PKPT')->badge(),
                        TextEntry::make('year')->label('Tahun'),
                        TextEntry::make('category')->label('Kategori')->badge()->formatStateUsing(fn (PkptCategory $state): string => $state->label()),
                        TextEntry::make('assignment_type')->label('Jenis penugasan'),
                        TextEntry::make('assignment')->label('Uraian penugasan')->columnSpan(2),
                        TextEntry::make('audit_object')->label('Obrik')->placeholder('—')->columnSpan(2),
                        TextEntry::make('executor')->label('Pelaksana'),
                        TextEntry::make('apip_count')->label('APIP')->numeric(),
                    ]),
                Section::make('Realisasi terkini')
                    ->columns(4)
                    ->schema([
                        TextEntry::make('status')->label('Status')->badge()->formatStateUsing(fn (PkptStatus $state): string => $state->label())->color(fn (PkptStatus $state): string => $state->color()),
                        TextEntry::make('progress')->label('Progres')->suffix('%'),
                        TextEntry::make('planned_start')->label('Rencana mulai')->date('d M Y')->placeholder('—'),
                        TextEntry::make('planned_end')->label('Rencana selesai')->date('d M Y')->placeholder('—'),
                        TextEntry::make('actual_start')->label('Realisasi mulai')->date('d M Y')->placeholder('—'),
                        TextEntry::make('actual_end')->label('Realisasi selesai')->date('d M Y')->placeholder('—'),
                        TextEntry::make('teamMembers.full_name')->label('Tim pelaksana')->badge()->columnSpan(2),
                        TextEntry::make('notes')->label('Catatan')->placeholder('—')->columnSpanFull(),
                    ]),
            ]);
    }
}
