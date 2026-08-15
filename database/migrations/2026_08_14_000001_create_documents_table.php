<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('documents', function (Blueprint $table): void {
            $table->id();
            $table->unsignedSmallInteger('year')->default(2026);
            $table->string('category', 30);
            $table->string('title');
            $table->string('document_number')->nullable();
            $table->date('document_date')->nullable();
            $table->text('description')->nullable();
            $table->string('file_path');
            $table->string('original_name');
            $table->string('mime_type')->nullable();
            $table->unsignedBigInteger('file_size')->nullable();
            $table->foreignId('spt_record_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('uploaded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['year', 'category']);
            $table->index(['category', 'document_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
