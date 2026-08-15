<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pkpt_activity_team_member', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pkpt_activity_id')->constrained()->cascadeOnDelete();
            $table->foreignId('team_member_id')->constrained()->cascadeOnDelete();
            $table->string('role')->default('Anggota');
            $table->timestamps();

            $table->unique(['pkpt_activity_id', 'team_member_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pkpt_activity_team_member');
    }
};
