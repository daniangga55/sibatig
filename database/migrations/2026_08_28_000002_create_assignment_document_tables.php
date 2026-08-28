<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('work_papers', function (Blueprint $table): void {
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

        Schema::create('assignment_reports', function (Blueprint $table): void {
            $table->id();
            $table->unsignedSmallInteger('year')->default(2026)->index();
            $table->foreignId('spt_record_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->string('report_number')->nullable();
            $table->date('report_date')->nullable();
            $table->text('description')->nullable();
            $table->string('storage_disk')->default('local');
            $table->string('file_path');
            $table->string('original_name');
            $table->string('mime_type')->nullable();
            $table->unsignedBigInteger('file_size')->nullable();
            $table->foreignId('uploaded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['year', 'report_date']);
            $table->index('report_number');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('assignment_reports');
        Schema::dropIfExists('work_papers');
    }
};
