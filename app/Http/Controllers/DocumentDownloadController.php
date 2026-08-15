<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DocumentDownloadController extends Controller
{
    use AuthorizesRequests;

    public function __invoke(Document $document): StreamedResponse
    {
        $this->authorize('view', $document);

        abort_unless(Storage::disk('local')->exists($document->file_path), 404, 'File dokumen tidak ditemukan.');

        return Storage::disk('local')->download(
            $document->file_path,
            $document->original_name,
            array_filter(['Content-Type' => $document->mime_type]),
        );
    }
}
