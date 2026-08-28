<?php

namespace App\Filament\Resources\SptRecords\Schemas;

use App\Models\NonPkptActivity;
use App\Models\PkptActivity;
use App\Models\SptRecord;
use App\Rules\WorkingDay;
use App\Support\DocumentStorage;
use App\Support\GoogleDriveStorage;
use App\Support\IndonesiaHolidayCalendar;
use App\Support\SptDocumentSync;
use Filament\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class SptRecordForm
{
    public static function configure(Schema $schema, string $scope = 'PKPT'): Schema
    {
        $isPkpt = $scope === 'PKPT';
        $relationship = $isPkpt ? 'pkptActivity' : 'nonPkptActivity';
        $foreignKey = $isPkpt ? 'pkpt_activity_id' : 'non_pkpt_activity_id';

        return $schema->components([
            Tabs::make('Form Surat Perintah Tugas')
                ->livewireProperty('activeSptTab')
                ->contained()
                ->extraAttributes(['class' => 'sibatig-spt-tabs'])
                ->tabs([
                    1 => Tab::make('Identitas SPT')
                        ->icon(Heroicon::OutlinedIdentification)
                        ->badge('1')
                        ->columns(3)
                        ->schema([
                            TextInput::make('year')->label('Tahun')->numeric()->default(2026)->required(),
                            TextInput::make('source_number')->label('Nomor urut')->numeric()->required(),
                            TextInput::make('document_number')->label('Nomor SPT')->required()->maxLength(255),
                            DatePicker::make('document_date')->label('Tanggal SPT')->native(false)->displayFormat('d/m/Y')->required(),
                            DatePicker::make('start_date')
                                ->label('Mulai pelaksanaan')
                                ->native(false)
                                ->displayFormat('d/m/Y')
                                ->weekStartsOnMonday()
                                ->minDate('2026-01-01')
                                ->maxDate('2026-12-31')
                                ->disabledDates(IndonesiaHolidayCalendar::nonWorkingDates(2026))
                                ->rules([new WorkingDay])
                                ->helperText('Hari kerja Senin–Jumat, selain libur nasional dan cuti bersama.')
                                ->required(),
                            DatePicker::make('end_date')
                                ->label('Selesai pelaksanaan')
                                ->native(false)
                                ->displayFormat('d/m/Y')
                                ->weekStartsOnMonday()
                                ->minDate('2026-01-01')
                                ->maxDate('2026-12-31')
                                ->disabledDates(IndonesiaHolidayCalendar::nonWorkingDates(2026))
                                ->rules([new WorkingDay])
                                ->afterOrEqual('start_date'),
                            Textarea::make('subject')->label('Uraian penugasan')->rows(4)->required()->columnSpanFull(),
                            Textarea::make('audit_object')->label('Objek pemeriksaan')->rows(3)->columnSpanFull(),
                            Actions::make([
                                Action::make('nextToAssignmentIntegration')
                                    ->label("Selanjutnya: Integrasi {$scope}")
                                    ->icon(Heroicon::OutlinedArrowRight)
                                    ->iconPosition('after')
                                    ->action(fn ($livewire) => $livewire->advanceSptTab(1)),
                            ])->alignEnd()->columnSpanFull(),
                        ]),
                    2 => Tab::make("Integrasi {$scope} & Laporan")
                        ->icon(Heroicon::OutlinedLink)
                        ->badge('2')
                        ->extraAttributes(fn ($livewire): array => self::tabAccessAttributes($livewire, 2))
                        ->columns(3)
                        ->schema([
                            Select::make($foreignKey)
                                ->label("Kegiatan {$scope}")
                                ->relationship($relationship, 'assignment', modifyQueryUsing: fn ($query) => $query->where('year', 2026)->orderBy('source_number'))
                                ->getOptionLabelFromRecordUsing(fn (PkptActivity|NonPkptActivity $record): string => "#{$record->source_number} · {$record->assignment}")
                                ->searchable(['assignment', 'source_number'])
                                ->preload()
                                ->required(),
                            Hidden::make('relation_type')->default($scope)->required(),
                            Select::make('match_type')->label('Jenis kecocokan')->options(['exact' => 'Tepat', 'thematic' => 'Tematik', 'contextual' => 'Kontekstual'])->native(false),
                            Select::make('assignment_type')->label('Jenis penugasan')->options(['AUDIT' => 'Audit', 'REVIU' => 'Reviu', 'MONITORING' => 'Monitoring', 'EVALUASI' => 'Evaluasi', 'PENDAMPINGAN' => 'Pendampingan', 'MANDATORY' => 'Mandatory'])->required()->native(false),
                            Select::make('status')->label('Status')->options(['SELESAI' => 'Selesai', 'ON PROGRES' => 'On progress'])->required()->native(false),
                            DatePicker::make('report_due_date')->label('Batas laporan')->native(false)->displayFormat('d/m/Y'),
                            TextInput::make('report_number')->label('Nomor laporan')->maxLength(255)->columnSpan(2),
                            DatePicker::make('report_date')->label('Tanggal laporan')->native(false)->displayFormat('d/m/Y'),
                            Textarea::make('notes')->label('Catatan')->rows(3)->columnSpanFull(),
                            Actions::make([
                                Action::make('backToIdentity')->label('Kembali')->color('gray')->icon(Heroicon::OutlinedArrowLeft)->action(fn ($livewire) => $livewire->returnToSptTab(1)),
                                Action::make('nextToSptFile')->label('Selanjutnya: File SPT')->icon(Heroicon::OutlinedArrowRight)->iconPosition('after')->action(fn ($livewire) => $livewire->advanceSptTab(2)),
                            ])->alignBetween()->columnSpanFull(),
                        ]),
                    3 => Tab::make('File SPT')
                        ->icon(Heroicon::OutlinedPaperClip)
                        ->badge('3')
                        ->extraAttributes(fn ($livewire): array => self::tabAccessAttributes($livewire, 3))
                        ->schema([
                            FileUpload::make('spt_file')
                                ->label('Upload file SPT')
                                ->disk(fn (?SptRecord $record): string => SptDocumentSync::diskNameFor($record))
                                ->directory(fn ($get, ?SptRecord $record): string => GoogleDriveStorage::path(
                                    $scope,
                                    GoogleDriveStorage::SPT,
                                    self::uploadYear($get, $record),
                                ))
                                ->getUploadedFileNameForStorageUsing(
                                    fn ($file): string => $file->getClientOriginalName(),
                                )
                                ->saveUploadedFileUsing(fn (
                                    TemporaryUploadedFile $file,
                                    Get $get,
                                    ?SptRecord $record,
                                ): ?string => GoogleDriveStorage::storeUploadedFile(
                                    $file,
                                    SptDocumentSync::diskNameFor($record),
                                    GoogleDriveStorage::path(
                                        $scope,
                                        GoogleDriveStorage::SPT,
                                        self::uploadYear($get, $record),
                                    ),
                                ))
                                ->visibility('private')
                                ->acceptedFileTypes([
                                    'application/pdf',
                                    'application/msword',
                                    'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                                    'image/jpeg',
                                    'image/png',
                                ])
                                ->maxSize(20480)
                                ->storeFileNamesIn('spt_file_original_name')
                                ->previewable(false)
                                ->downloadable()
                                ->getUploadedFileUsing(fn (string $file, string|array|null $storedFileNames, ?SptRecord $record): ?array => DocumentStorage::uploadedFileData(
                                    $record ? SptDocumentSync::documentFor($record) : null,
                                    $file,
                                    $storedFileNames,
                                ))
                                ->getDownloadableFileUrlUsing(function (?SptRecord $record): ?string {
                                    $document = $record
                                        ? SptDocumentSync::documentFor($record)
                                        : null;

                                    return $document
                                        ? route('documents.download', $document)
                                        : null;
                                })
                                ->preventFilePathTampering(
                                    allowFilePathUsing: fn (
                                        string $file,
                                        ?SptRecord $record
                                    ): bool => $record?->documents()
                                        ->where('source', SptDocumentSync::SOURCE)
                                        ->where('file_path', $file)
                                        ->exists() ?? false,
                                )
                                ->helperText("File otomatis masuk ke Google Drive: SIBATIG/{$scope}/SPT/{tahun}. Nama file asli dipertahankan; maksimal 20 MB."),
                            Actions::make([
                                Action::make('backToAssignmentIntegration')->label("Kembali ke Integrasi {$scope}")->color('gray')->icon(Heroicon::OutlinedArrowLeft)->action(fn ($livewire) => $livewire->returnToSptTab(2)),
                            ])->alignStart()->columnSpanFull(),
                        ]),
                ])
                ->columnSpanFull(),
        ]);
    }

    private static function uploadYear(mixed $get, ?SptRecord $record): int|string
    {
        $year = null;

        if (is_callable($get)) {
            try {
                $year = $get('year');
            } catch (\Throwable) {
                $year = null;
            }
        }

        return $year ?: ($record?->year ?: date('Y'));
    }

    private static function tabAccessAttributes(object $livewire, int $tab): array
    {
        if ($livewire->canAccessSptTab($tab)) {
            return ['data-spt-tab-status' => 'available'];
        }

        return [
            'aria-disabled' => 'true',
            'data-spt-tab-status' => 'locked',
            'disabled' => true,
            'title' => 'Selesaikan tab sebelumnya terlebih dahulu',
        ];
    }
}
