<?php

namespace App\Filament\Support;

use App\Models\AssignmentReport;
use App\Models\SptRecord;
use App\Models\WorkPaper;
use App\Support\AssignmentFileStorage;
use App\Support\GoogleDriveStorage;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

class AssignmentDocumentForm
{
    public static function workPaper(Schema $schema, string $scope): Schema
    {
        return self::configure($schema, $scope, 'work-paper');
    }

    public static function assignmentReport(Schema $schema, string $scope): Schema
    {
        return self::configure($schema, $scope, 'assignment-report');
    }

    private static function configure(Schema $schema, string $scope, string $type): Schema
    {
        $isReport = $type === 'assignment-report';
        $label = $scope === 'PKPT' ? 'PKPT' : 'Non-PKPT';
        $modelClass = $isReport ? AssignmentReport::class : WorkPaper::class;
        $routeName = $isReport ? 'assignment-reports.download' : 'work-papers.download';
        $documentType = $isReport ? GoogleDriveStorage::REPORT : GoogleDriveStorage::WORK_PAPER;
        $acceptedTypes = $isReport
            ? ['application/pdf']
            : [
                'application/vnd.ms-excel',
                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'application/msword',
                'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            ];

        return $schema->components([
            Section::make($isReport ? "Laporan Hasil Penugasan {$label}" : "Kertas Kerja {$label}")
                ->description('File disimpan privat dan wajib terhubung ke Surat Perintah Tugas.')
                ->columns(3)
                ->schema([
                    Select::make('spt_record_id')
                        ->label('Surat Perintah Tugas')
                        ->relationship('sptRecord', 'document_number', modifyQueryUsing: fn ($query) => $query->where('relation_type', $scope)->orderByDesc('document_date'))
                        ->getOptionLabelFromRecordUsing(fn (SptRecord $record): string => "{$record->document_number} · {$record->assignment_type} · {$record->subject}")
                        ->searchable(['document_number', 'subject', 'assignment_type'])
                        ->preload()
                        ->required()
                        ->columnSpanFull(),
                    TextInput::make('year')->label('Tahun')->numeric()->default(2026)->required(),
                    TextInput::make('title')->label('Judul dokumen')->required()->maxLength(255)->columnSpan(2),
                    ...($isReport ? [
                        TextInput::make('report_number')->label('Nomor laporan')->maxLength(255)->columnSpan(2),
                        DatePicker::make('report_date')->label('Tanggal laporan')->native(false)->displayFormat('d/m/Y'),
                    ] : [
                        DatePicker::make('document_date')->label('Tanggal dokumen')->native(false)->displayFormat('d/m/Y'),
                    ]),
                    FileUpload::make('file_path')
                        ->label($isReport ? 'File laporan PDF' : 'File kertas kerja')
                        ->disk(fn (WorkPaper|AssignmentReport|null $record): string => AssignmentFileStorage::diskName($record))
                        ->directory(fn (Get $get): string => GoogleDriveStorage::path(
                            $scope,
                            $documentType,
                            $get('year') ?: date('Y'),
                        ))
                        ->getUploadedFileNameForStorageUsing(
                            fn ($file): string => $file->getClientOriginalName(),
                        )
                        ->visibility('private')
                        ->acceptedFileTypes($acceptedTypes)
                        ->maxSize(20480)
                        ->storeFileNamesIn('original_name')
                        ->previewable(false)
                        ->downloadable()
                        ->getUploadedFileUsing(fn (string $file, string|array|null $storedFileNames, WorkPaper|AssignmentReport|null $record): ?array => AssignmentFileStorage::uploadedFileData(
                            $record,
                            $file,
                            $storedFileNames,
                            $routeName,
                        ))
                        ->getDownloadableFileUrlUsing(fn (WorkPaper|AssignmentReport|null $record): ?string => $record ? route($routeName, $record) : null)
                        ->preventFilePathTampering(allowFilePathUsing: fn (string $file, WorkPaper|AssignmentReport|null $record): bool => $record?->file_path === $file)
                        ->required(fn (WorkPaper|AssignmentReport|null $record): bool => $record === null)
                        ->helperText(
                            ($isReport ? 'Hanya PDF' : 'Format XLSX, XLS, DOCX, atau DOC')
                            .". Disimpan ke Google Drive: SIBATIG/{$scope}/{$documentType}/{tahun}; nama file asli dipertahankan. Maksimal 20 MB."
                        )
                        ->columnSpanFull(),
                    Textarea::make('description')->label('Keterangan')->rows(4)->columnSpanFull(),
                ]),
        ]);
    }
}
