<?php

namespace App\Filament\Resources\Documents\Schemas;

use App\Enums\DocumentCategory;
use App\Models\Document;
use App\Models\SptRecord;
use App\Support\DocumentStorage;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

class DocumentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Berkas dokumen')
                ->description('Berkas disimpan secara privat dan hanya dapat diunduh oleh pengguna yang sudah masuk.')
                ->columns(3)
                ->schema([
                    Select::make('category')
                        ->label('Kategori dokumen')
                        ->options(DocumentCategory::options())
                        ->native(false)
                        ->live()
                        ->required(),
                    TextInput::make('year')
                        ->label('Tahun')
                        ->numeric()
                        ->minValue(2026)
                        ->maxValue(2100)
                        ->default(2026)
                        ->required(),
                    DatePicker::make('document_date')
                        ->label('Tanggal dokumen')
                        ->native(false)
                        ->displayFormat('d/m/Y'),
                    TextInput::make('title')
                        ->label('Judul dokumen')
                        ->maxLength(255)
                        ->required()
                        ->columnSpan(2),
                    TextInput::make('document_number')
                        ->label('Nomor dokumen')
                        ->maxLength(255),
                    FileUpload::make('file_path')
                        ->label('Pilih file')
                        ->disk(fn (?Document $record): string => DocumentStorage::diskName($record))
                        ->directory(fn (Get $get): string => 'documents/'.($get('year') ?: 2026).'/'.(DocumentCategory::tryFrom((string) $get('category'))?->directory() ?? 'lainnya'))
                        ->visibility('private')
                        ->acceptedFileTypes([
                            'application/pdf',
                            'application/msword',
                            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                            'application/vnd.ms-excel',
                            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                            'application/vnd.ms-powerpoint',
                            'application/vnd.openxmlformats-officedocument.presentationml.presentation',
                            'image/jpeg',
                            'image/png',
                        ])
                        ->maxSize(20480)
                        ->storeFileNamesIn('original_name')
                        ->previewable(false)
                        ->downloadable()
                        ->getUploadedFileUsing(
                            fn (string $file, string|array|null $storedFileNames, ?Document $record): ?array => DocumentStorage::uploadedFileData($record, $file, $storedFileNames),
                        )
                        ->getDownloadableFileUrlUsing(
                            fn (?Document $record): ?string => $record ? route('documents.download', $record) : null,
                        )
                        ->required(fn (?Document $record): bool => $record === null)
                        ->helperText('PDF, Word, Excel, PowerPoint, JPG, atau PNG. Maksimal 20 MB.')
                        ->columnSpanFull(),
                ]),
            Section::make('Integrasi dan keterangan')
                ->columns(2)
                ->schema([
                    Select::make('spt_record_id')
                        ->label('Terkait Rekap SPT')
                        ->relationship('sptRecord', 'document_number', modifyQueryUsing: fn ($query) => $query->where('year', 2026)->orderBy('source_number'))
                        ->getOptionLabelFromRecordUsing(fn (SptRecord $record): string => "#{$record->source_number} · {$record->document_number} · {$record->subject}")
                        ->searchable(['document_number', 'subject'])
                        ->preload()
                        ->helperText('Opsional. Gunakan untuk menghubungkan file SPT atau laporan ke Rekap SPT.')
                        ->columnSpanFull(),
                    Textarea::make('description')
                        ->label('Keterangan')
                        ->rows(4)
                        ->columnSpanFull(),
                ]),
        ]);
    }
}
