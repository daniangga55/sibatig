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
        Schema::create('monitoring_evaluations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pkpt_activity_id')->constrained()->cascadeOnDelete();
            $table->date('evaluation_date')->index();
            $table->string('status')->index();
            $table->unsignedTinyInteger('progress')->default(0);
            $table->string('stage')->nullable();
            $table->date('actual_start')->nullable();
            $table->date('actual_end')->nullable();
            $table->text('achievement')->nullable();
            $table->text('obstacles')->nullable();
            $table->text('follow_up')->nullable();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('monitoring_evaluations');
    }
};
