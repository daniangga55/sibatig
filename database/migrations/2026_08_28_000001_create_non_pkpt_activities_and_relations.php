<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('non_pkpt_activities', function (Blueprint $table): void {
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
            $table->index(['year', 'status']);
        });

        Schema::create('non_pkpt_activity_team_member', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('non_pkpt_activity_id')->constrained()->cascadeOnDelete();
            $table->foreignId('team_member_id')->constrained()->cascadeOnDelete();
            $table->string('role')->default('Anggota');
            $table->timestamps();

            $table->unique(['non_pkpt_activity_id', 'team_member_id'], 'non_pkpt_team_unique');
        });

        Schema::table('monitoring_evaluations', function (Blueprint $table): void {
            $table->unsignedBigInteger('pkpt_activity_id')->nullable()->change();
            $table->foreignId('non_pkpt_activity_id')
                ->nullable()
                ->after('pkpt_activity_id')
                ->constrained()
                ->cascadeOnDelete();
        });

        Schema::table('spt_records', function (Blueprint $table): void {
            $table->foreignId('non_pkpt_activity_id')
                ->nullable()
                ->after('pkpt_activity_id')
                ->constrained()
                ->nullOnDelete();
        });

        $this->backfillNonPkptActivities();
    }

    public function down(): void
    {
        DB::table('monitoring_evaluations')->whereNotNull('non_pkpt_activity_id')->delete();

        Schema::table('spt_records', function (Blueprint $table): void {
            $table->dropConstrainedForeignId('non_pkpt_activity_id');
        });

        Schema::table('monitoring_evaluations', function (Blueprint $table): void {
            $table->dropConstrainedForeignId('non_pkpt_activity_id');
            $table->unsignedBigInteger('pkpt_activity_id')->nullable(false)->change();
        });

        Schema::dropIfExists('non_pkpt_activity_team_member');
        Schema::dropIfExists('non_pkpt_activities');
    }

    private function backfillNonPkptActivities(): void
    {
        DB::table('spt_records')
            ->where('relation_type', 'NON PKPT')
            ->orderBy('year')
            ->orderBy('source_number')
            ->each(function (object $spt): void {
                $activityId = DB::table('non_pkpt_activities')
                    ->where('year', $spt->year)
                    ->where('source_number', $spt->source_number)
                    ->value('id');

                if (! $activityId) {
                    $status = strtoupper((string) $spt->status) === 'SELESAI'
                        ? 'selesai'
                        : 'sedang_berjalan';

                    $activityId = DB::table('non_pkpt_activities')->insertGetId([
                        'year' => $spt->year,
                        'source_number' => $spt->source_number,
                        'category' => $this->categoryFrom((string) $spt->assignment_type),
                        'assignment_type' => $spt->assignment_type,
                        'assignment' => $spt->subject,
                        'audit_object' => $spt->audit_object,
                        'executor' => 'IRBAN III',
                        'apip_count' => 0,
                        'status' => $status,
                        'progress' => $status === 'selesai' ? 100 : 50,
                        'planned_start' => $spt->start_date,
                        'planned_end' => $spt->end_date,
                        'actual_start' => $spt->start_date,
                        'actual_end' => $status === 'selesai' ? $spt->end_date : null,
                        'notes' => 'Dibentuk otomatis dari data SPT Non-PKPT yang sudah ada.',
                        'created_at' => $spt->created_at,
                        'updated_at' => $spt->updated_at,
                    ]);
                }

                DB::table('spt_records')
                    ->where('id', $spt->id)
                    ->update(['non_pkpt_activity_id' => $activityId]);
            });
    }

    private function categoryFrom(string $assignmentType): string
    {
        $type = strtoupper($assignmentType);

        return match (true) {
            str_contains($type, 'AUDIT') => 'audit',
            str_contains($type, 'REVIU') => 'reviu',
            str_contains($type, 'MONITOR') => 'monitoring',
            str_contains($type, 'EVALUASI') => 'evaluasi',
            str_contains($type, 'PENDAMPING') => 'pendampingan',
            default => 'mandatory',
        };
    }
};
