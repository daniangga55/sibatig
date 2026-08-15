<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('website_settings', function (Blueprint $table) {
            $table->id();
            $table->string('site_name')->default('SIBATIG');
            $table->string('site_tagline')->nullable();
            $table->string('organization_name');
            $table->text('description')->nullable();
            $table->string('primary_color', 20)->default('#0f766e');
            $table->string('accent_color', 20)->default('#f59e0b');
            $table->string('logo_path')->nullable();
            $table->string('contact_email')->nullable();
            $table->string('contact_phone', 30)->nullable();
            $table->string('address')->nullable();
            $table->string('timezone')->default('Asia/Jakarta');
            $table->string('locale', 10)->default('id');
            $table->unsignedSmallInteger('active_year')->default(2026);
            $table->boolean('maintenance_mode')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('website_settings');
    }
};
