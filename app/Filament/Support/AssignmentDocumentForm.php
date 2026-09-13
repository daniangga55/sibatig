<?php

namespace App\Filament\Support;

use App\Models\AssignmentReport;
use App\Models\SptRecord;
use App\Models\SupportingDocument;
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
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

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

    public static function supportingDocument(Schema $schema, string $scope): Schema
    {
        return self::configure($schema, $scope, 'supporting-document');
    }

    private static function configure(Schema $schema, string $scope, string $type): Schema
    {
        $isReport = $type === 'assignment-report';
        $isSupportingDocument = $type === 'supporting-document';
        $label = $scope === 'PKPT' ? 'PKPT' : 'Non-PKPT';
        $routeName = match ($type) {
            'assignment-report' => 'assignment-reports.download',
            'supporting-document' => 'supporting-documents.download',
            default => 'work-papers.download',
        };
        $documentType = match ($type) {
            'assignment-report' => GoogleDriveStorage::REPORT,
            'supporting-document' => GoogleDriveStorage::SUPPORTING_DOCUMENT,
            default => GoogleDriveStorage::WORK_PAPER,
        };
        $acceptedTypes = $isReport
            ? ['application/pdf']
            : ($isSupportingDocument ? [
                'application/pdf',
                'application/vnd.ms-excel',
                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'application/msword',
                'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                'application/vnd.ms-powerpoint',
                'application/vnd.openxmlformats-officedocument.presentationml.presentation',
                'image/jpeg',
                'image/png',
            ] : [
                'application/vnd.ms-excel',
                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'application/msword',
                'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            ]);

        $sectionTitle = match ($type) {
            'assignment-report' => "Laporan Hasil Penugasan {$label}",
            'supporting-document' => "Dokumen Pendukung {$label}",
            default => "Kertas Kerja {$label}",
        };

        return $schema->components([
            Section::make($sectionTitle)
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
                        ->label(match ($type) {
                            'assignment-report' => 'File laporan PDF',
                            'supporting-document' => 'File dokumen pendukung',
                            default => 'File kertas kerja',
                        })
                        ->disk(fn (WorkPaper|AssignmentReport|SupportingDocument|null $record): string => AssignmentFileStorage::diskName($record))
                        ->directory(fn (Get $get): string => GoogleDriveStorage::path(
                            $scope,
                            $documentType,
                            $get('year') ?: date('Y'),
                        ))
                        ->getUploadedFileNameForStorageUsing(
                            fn ($file): string => $file->getClientOriginalName(),
                        )
                        ->saveUploadedFileUsing(fn (
                            TemporaryUploadedFile $file,
                            Get $get,
                            WorkPaper|AssignmentReport|SupportingDocument|null $record,
                        ): ?string => GoogleDriveStorage::storeUploadedFile(
                            $file,
                            AssignmentFileStorage::diskName($record),
                            GoogleDriveStorage::path(
                                $scope,
                                $documentType,
                                $get('year') ?: date('Y'),
                            ),
                        ))
                        ->visibility('private')
                        ->acceptedFileTypes($acceptedTypes)
                        ->maxSize(20480)
                        ->storeFileNamesIn('original_name')
                        ->previewable(false)
                        ->downloadable()
                        ->getUploadedFileUsing(fn (string $file, string|array|null $storedFileNames, WorkPaper|AssignmentReport|SupportingDocument|null $record): ?array => AssignmentFileStorage::uploadedFileData(
                            $record,
                            $file,
                            $storedFileNames,
                            $routeName,
                        ))
                        ->getDownloadableFileUrlUsing(fn (WorkPaper|AssignmentReport|SupportingDocument|null $record): ?string => $record ? route($routeName, $record) : null)
                        ->preventFilePathTampering(allowFilePathUsing: fn (string $file, WorkPaper|AssignmentReport|SupportingDocument|null $record): bool => $record?->file_path === $file)
                        ->required(fn (WorkPaper|AssignmentReport|SupportingDocument|null $record): bool => $record === null)
                        ->helperText(
                            ($isReport
                                ? 'Hanya PDF'
                                : ($isSupportingDocument ? 'Format PDF, XLSX, XLS, DOCX, DOC, PPTX, PPT, JPG, atau PNG' : 'Format XLSX, XLS, DOCX, atau DOC'))
                            .". Disimpan ke Google Drive: SIBATIG/{$scope}/{$documentType}/{tahun}; nama file asli dipertahankan. Maksimal 20 MB."
                        )
                        ->columnSpanFull(),
                    Textarea::make('description')->label('Keterangan')->rows(4)->columnSpanFull(),
                ]),
        ]);
    }
}
