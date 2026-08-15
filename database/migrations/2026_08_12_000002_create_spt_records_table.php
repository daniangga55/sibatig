<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('spt_records', function (Blueprint $table) {
            $table->id();
            $table->unsignedSmallInteger('year')->default(2026)->index();
            $table->unsignedSmallInteger('source_number');
            $table->string('document_number')->unique();
            $table->date('document_date');
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->date('report_due_date')->nullable();
            $table->text('subject');
            $table->text('audit_object')->nullable();
            $table->string('report_number')->nullable();
            $table->date('report_date')->nullable();
            $table->string('assignment_type')->index();
            $table->string('relation_type')->default('NON PKPT')->index();
            $table->string('status')->default('SELESAI')->index();
            $table->foreignId('pkpt_activity_id')->nullable()->constrained()->nullOnDelete();
            $table->string('match_type')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['year', 'source_number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('spt_records');
    }
};
