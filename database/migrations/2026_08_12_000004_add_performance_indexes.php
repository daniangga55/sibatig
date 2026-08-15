<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pkpt_activities', function (Blueprint $table) {
            $table->index(['year', 'status'], 'pkpt_year_status_index');
            $table->index(['year', 'progress'], 'pkpt_year_progress_index');
            $table->index(['year', 'category'], 'pkpt_year_category_index');
        });

        Schema::table('monitoring_evaluations', function (Blueprint $table) {
            $table->index(['pkpt_activity_id', 'evaluation_date'], 'monitoring_activity_date_index');
        });

        Schema::table('spt_records', function (Blueprint $table) {
            $table->index(['year', 'status'], 'spt_year_status_index');
            $table->index(['year', 'start_date'], 'spt_year_start_index');
            $table->index(['year', 'assignment_type'], 'spt_year_type_index');
        });

        Schema::table('team_members', function (Blueprint $table) {
            $table->index(['is_active', 'sort_order'], 'team_active_sort_index');
        });
    }

    public function down(): void
    {
        Schema::table('pkpt_activities', function (Blueprint $table) {
            $table->dropIndex('pkpt_year_status_index');
            $table->dropIndex('pkpt_year_progress_index');
            $table->dropIndex('pkpt_year_category_index');
        });

        Schema::table('monitoring_evaluations', function (Blueprint $table) {
            $table->dropIndex('monitoring_activity_date_index');
        });

        Schema::table('spt_records', function (Blueprint $table) {
            $table->dropIndex('spt_year_status_index');
            $table->dropIndex('spt_year_start_index');
            $table->dropIndex('spt_year_type_index');
        });

        Schema::table('team_members', function (Blueprint $table) {
            $table->dropIndex('team_active_sort_index');
        });
    }
};
