<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('supporting_documents', function (Blueprint $table): void {
            $table->id();
            $table->unsignedSmallInteger('year')->default(2026)->index();
            $table->foreignId('spt_record_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->date('document_date')->nullable();
            $table->text('description')->nullable();
            $table->string('storage_disk')->default('local');
            $table->string('file_path');
            $table->string('original_name');
            $table->string('mime_type')->nullable();
            $table->unsignedBigInteger('file_size')->nullable();
            $table->foreignId('uploaded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['year', 'document_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('supporting_documents');
    }
};
