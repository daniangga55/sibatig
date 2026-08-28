<?php

use App\Http\Controllers\AssignmentFileDownloadController;
use App\Http\Controllers\DocumentDownloadController;
use App\Http\Controllers\GoogleDriveOAuthController;
use Google\Client;
use Google\Service\Drive;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

Route::view('/', 'public.home')->name('home');
Route::view('/privacy-policy', 'public.privacy-policy')->name('privacy-policy');
Route::view('/terms-of-service', 'public.terms-of-service')->name('terms-of-service');

Route::get('/admin/documents/{document}/download', DocumentDownloadController::class)
    ->middleware('auth')
    ->name('documents.download');

Route::get('/admin/work-papers/{workPaper}/download', [AssignmentFileDownloadController::class, 'workPaper'])
    ->middleware('auth')
    ->name('work-papers.download');

Route::get('/admin/assignment-reports/{assignmentReport}/download', [AssignmentFileDownloadController::class, 'assignmentReport'])
    ->middleware('auth')
    ->name('assignment-reports.download');

Route::middleware(['auth', 'throttle:6,1'])->group(function (): void {
    Route::get('/admin/google-drive/oauth/authorize', [GoogleDriveOAuthController::class, 'authorizeDrive'])
        ->name('google-drive.oauth.authorize');
    Route::get('/admin/google-drive/oauth/callback', [GoogleDriveOAuthController::class, 'callback'])
        ->name('google-drive.oauth.callback');
});

Route::get('/test-google-upload', function () {
    try {
        $disk = Storage::disk('google');

        $path = 'test-'.now()->format('YmdHis').'.txt';

        $disk->put($path, 'Test Google Drive - '.now());

        return response()->json([
            'success' => true,
            'path' => $path,
            'message' => 'File berhasil dikirim ke Google Drive.',
        ]);
    } catch (Throwable $e) {
        return response()->json([
            'success' => false,
            'error' => get_class($e),
            'message' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
        ], 500);
    }
});

Route::get('/google-drive/auth', function () {
    $client = new Client;

    $client->setClientId(env('GOOGLE_DRIVE_CLIENT_ID'));
    $client->setClientSecret(env('GOOGLE_DRIVE_CLIENT_SECRET'));
    $client->setRedirectUri(url('/google-drive/callback'));

    $client->addScope(Drive::DRIVE);
    $client->setAccessType('offline');
    $client->setPrompt('consent');

    return redirect()->away($client->createAuthUrl());
});

Route::get('/google-drive/callback', function () {
    $client = new Client;

    $client->setClientId(env('GOOGLE_DRIVE_CLIENT_ID'));
    $client->setClientSecret(env('GOOGLE_DRIVE_CLIENT_SECRET'));
    $client->setRedirectUri(url('/google-drive/callback'));

    $token = $client->fetchAccessTokenWithAuthCode(
        request('code')
    );

    return response()->json([
        'refresh_token' => $token['refresh_token'] ?? null,
        'token' => $token,
    ]);
});

Route::match(['get', 'post'], '/test-google-drive', function (Request $request) {

    if ($request->isMethod('post')) {

        $request->validate([
            'document' => ['required', 'file', 'max:10240'],
        ]);

        $path = Storage::disk('google')->putFile(
            'documents',
            $request->file('document')
        );

        return response()->json([
            'success' => true,
            'message' => 'File berhasil diupload ke Google Drive.',
            'path' => $path,
        ]);
    }

    return <<<HTML
        <!DOCTYPE html>
        <html>
        <head>
            <title>Test Google Drive</title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    max-width: 600px;
                    margin: 50px auto;
                    padding: 20px;
                }

                form {
                    border: 1px solid #ddd;
                    padding: 25px;
                    border-radius: 10px;
                }

                input {
                    margin-bottom: 15px;
                }

                button {
                    background: #2563eb;
                    color: white;
                    border: none;
                    padding: 10px 20px;
                    border-radius: 6px;
                    cursor: pointer;
                }
            </style>
        </head>
        <body>

            <h2>Test Upload Google Drive</h2>

            <form method="POST" enctype="multipart/form-data">
                <input type="hidden" name="_token" value="{$request->session()->token()}">

                <input type="file" name="document" required>

                <br>

                <button type="submit">
                    Upload ke Google Drive
                </button>
            </form>

        </body>
        </html>
    HTML;
});
