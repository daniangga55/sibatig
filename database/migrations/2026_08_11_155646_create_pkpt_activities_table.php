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
        Schema::create('pkpt_activities', function (Blueprint $table) {
            $table->id();
            $table->unsignedSmallInteger('year')->default(2026)->index();
            $table->unsignedSmallInteger('source_number');
            $table->string('category')->index();
            $table->string('assignment_type');
            $table->text('assignment');
            $table->text('audit_object')->nullable();
            $table->string('executor')->default('IRBAN III');
            $table->unsignedSmallInteger('apip_count')->default(0);
            $table->string('status')->default('belum_dilaksanakan')->index();
            $table->unsignedTinyInteger('progress')->default(0);
            $table->date('planned_start')->nullable();
            $table->date('planned_end')->nullable();
            $table->date('actual_start')->nullable();
            $table->date('actual_end')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['year', 'source_number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pkpt_activities');
    }
};
