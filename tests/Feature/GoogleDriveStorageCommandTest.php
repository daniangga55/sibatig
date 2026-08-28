<?php

namespace Tests\Feature;

use App\Support\GoogleDriveStorage;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class GoogleDriveStorageCommandTest extends TestCase
{
    public function test_write_check_prepares_all_assignment_directories(): void
    {
        config([
            'filesystems.disks.google.clientId' => 'testing-client-id',
            'filesystems.disks.google.clientSecret' => 'testing-client-secret',
            'filesystems.disks.google.refreshToken' => 'testing-refresh-token',
            'filesystems.disks.google.folder' => 'SIBATIG',
        ]);
        Storage::fake('google');

        $this->artisan('sibatig:gdrive-check', ['--write' => true, '--year' => '2026'])
            ->assertSuccessful();

        foreach ([GoogleDriveStorage::PKPT, GoogleDriveStorage::NON_PKPT] as $scope) {
            foreach ([GoogleDriveStorage::SPT, GoogleDriveStorage::WORK_PAPER, GoogleDriveStorage::REPORT] as $type) {
                $this->assertTrue(
                    Storage::disk('google')->directoryExists(GoogleDriveStorage::path($scope, $type, 2026)),
                );
            }
        }
    }
}
