<?php

namespace App\Http\Controllers;

use App\Models\AssignmentReport;
use App\Models\WorkPaper;
use App\Support\AssignmentFileStorage;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AssignmentFileDownloadController extends Controller
{
    use AuthorizesRequests;

    public function workPaper(WorkPaper $workPaper): StreamedResponse
    {
        return $this->download($workPaper);
    }

    public function assignmentReport(AssignmentReport $assignmentReport): StreamedResponse
    {
        return $this->download($assignmentReport);
    }

    private function download(WorkPaper|AssignmentReport $file): StreamedResponse
    {
        $this->authorize('view', $file);

        $disk = AssignmentFileStorage::disk($file);

        abort_unless($disk->exists($file->file_path), 404, 'File penugasan tidak ditemukan.');

        return $disk->download(
            $file->file_path,
            $file->original_name,
            array_filter(['Content-Type' => $file->mime_type]),
        );
    }
}
