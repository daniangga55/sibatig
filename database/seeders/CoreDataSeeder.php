<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\User;
use App\Models\WebsiteSetting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class CoreDataSeeder extends Seeder
{
    public function run(): void
    {
        User::query()->firstOrCreate(
            ['email' => 'admin@sibatig.local'],
            [
                'name' => 'Administrator SIBATIG',
                'password' => Hash::make('Sibatig2026!'),
                'role' => UserRole::SuperAdmin,
                'is_active' => true,
                'email_verified_at' => now(),
            ],
        );

        WebsiteSetting::query()->firstOrCreate(
            ['id' => 1],
            [
                'site_name' => 'SIBATIG',
                'site_tagline' => 'Sistem Informasi Irban Tiga',
                'organization_name' => 'Inspektorat Kota Kediri',
                'description' => 'Platform pengelolaan PKPT, tim, serta monitoring dan evaluasi Irban Tiga.',
                'theme_preset' => 'ocean',
                'primary_color' => '#1769d2',
                'accent_color' => '#f3b73f',
                'sidebar_color' => '#061b3b',
                'canvas_color' => '#f4f7fb',
                'contact_email' => 'inspektorat@kedirikota.go.id',
                'timezone' => 'Asia/Jakarta',
                'locale' => 'id',
                'active_year' => 2026,
            ],
        );
    }
}
