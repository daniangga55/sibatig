<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Support\DocumentStorage;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DocumentDownloadController extends Controller
{
    use AuthorizesRequests;

    public function __invoke(Document $document): StreamedResponse
    {
        $this->authorize('view', $document);

        $disk = DocumentStorage::disk($document);

        abort_unless($disk->exists($document->file_path), 404, 'File dokumen tidak ditemukan.');

        return $disk->download(
            $document->file_path,
            $document->original_name,
            array_filter(['Content-Type' => $document->mime_type]),
        );
    }
}
