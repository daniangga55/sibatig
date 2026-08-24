<?php

use App\Http\Controllers\DocumentDownloadController;
use App\Http\Controllers\GoogleDriveOAuthController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'public.home')->name('home');
Route::view('/privacy-policy', 'public.privacy-policy')->name('privacy-policy');
Route::view('/terms-of-service', 'public.terms-of-service')->name('terms-of-service');

Route::get('/admin/documents/{document}/download', DocumentDownloadController::class)
    ->middleware('auth')
    ->name('documents.download');

Route::middleware(['auth', 'throttle:6,1'])->group(function (): void {
    Route::get('/admin/google-drive/oauth/authorize', [GoogleDriveOAuthController::class, 'authorizeDrive'])
        ->name('google-drive.oauth.authorize');
    Route::get('/admin/google-drive/oauth/callback', [GoogleDriveOAuthController::class, 'callback'])
        ->name('google-drive.oauth.callback');
});
