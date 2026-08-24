<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');

        $response
            ->assertOk()
            ->assertSee('SIBATIG')
            ->assertSee('Pengelolaan kegiatan Irban 3')
            ->assertSee(route('privacy-policy'), false);
    }

    public function test_google_verification_pages_are_publicly_accessible(): void
    {
        $this->get('/privacy-policy')
            ->assertOk()
            ->assertSee('Google User Data Disclosure')
            ->assertSee('drive.file');

        $this->get('/terms-of-service')
            ->assertOk()
            ->assertSee('Ketentuan penggunaan SIBATIG');
    }
}
