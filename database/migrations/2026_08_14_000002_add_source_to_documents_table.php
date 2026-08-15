<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('documents', function (Blueprint $table): void {
            $table->string('source', 30)->nullable()->after('category');
            $table->unique(['spt_record_id', 'source'], 'documents_spt_source_unique');
        });
    }

    public function down(): void
    {
        Schema::table('documents', function (Blueprint $table): void {
            $table->dropUnique('documents_spt_source_unique');
            $table->dropColumn('source');
        });
    }
};
