<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('website_settings')
            ->whereIn('organization_name', ['Inspektorat Daerah', 'Inspektorat Daerah Kota Kediri'])
            ->update(['organization_name' => 'Inspektorat Kota Kediri']);
    }

    public function down(): void
    {
        DB::table('website_settings')
            ->where('organization_name', 'Inspektorat Kota Kediri')
            ->update(['organization_name' => 'Inspektorat Daerah Kota Kediri']);
    }
};
