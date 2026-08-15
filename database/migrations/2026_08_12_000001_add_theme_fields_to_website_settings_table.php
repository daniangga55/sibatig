<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('website_settings', function (Blueprint $table) {
            $table->string('theme_preset', 30)->default('ocean')->after('description');
            $table->string('sidebar_color', 20)->default('#061b3b')->after('accent_color');
            $table->string('canvas_color', 20)->default('#f4f7fb')->after('sidebar_color');
        });
    }

    public function down(): void
    {
        Schema::table('website_settings', function (Blueprint $table) {
            $table->dropColumn(['theme_preset', 'sidebar_color', 'canvas_color']);
        });
    }
};
