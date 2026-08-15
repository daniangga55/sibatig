<?php

namespace Database\Seeders;

use App\Enums\PkptStatus;
use App\Models\MonitoringEvaluation;
use App\Models\PkptActivity;
use Illuminate\Database\Seeder;

class MonitoringEvaluationSeeder extends Seeder
{
    public function run(): void
    {
        $realizations = [
            21 => ['2026-01-05', '2026-01-09', '2026-01-12', 100],
            7 => ['2026-01-12', '2026-01-30', '2026-02-09', 100],
            32 => ['2026-01-22', '2026-04-02', '2026-04-06', 100],
            48 => ['2026-02-09', '2026-02-13', '2026-02-23', 100],
            1 => ['2026-01-12', '2026-06-05', '2026-06-08', 100],
            33 => ['2026-02-18', '2026-07-03', '2026-07-03', 100],
            50 => ['2026-06-08', '2026-07-24', '2026-07-24', 100],
            28 => ['2026-06-22', '2026-06-26', '2026-06-30', 100],
            31 => ['2026-07-13', '2026-07-29', '2026-07-31', 100],
            29 => ['2026-07-13', '2026-07-17', '2026-07-20', 100],
            34 => ['2026-04-13', null, '2026-08-10', 75],
        ];

        foreach ($realizations as $number => [$start, $end, $date, $progress]) {
            $activity = PkptActivity::query()
                ->where('year', 2026)
                ->where('source_number', $number)
                ->firstOrFail();
            $status = $progress === 100 ? PkptStatus::Selesai : PkptStatus::Berjalan;

            $existing = MonitoringEvaluation::query()
                ->withTrashed()
                ->where('pkpt_activity_id', $activity->id)
                ->whereDate('evaluation_date', $date)
                ->exists();

            if (! $existing) {
                MonitoringEvaluation::query()->create([
                    'pkpt_activity_id' => $activity->id,
                    'evaluation_date' => $date,
                    'status' => $status,
                    'progress' => $progress,
                    'stage' => $progress === 100 ? 'Laporan selesai' : 'Pelaksanaan reviu',
                    'actual_start' => $start,
                    'actual_end' => $end,
                    'achievement' => $progress === 100 ? 'Kegiatan dan pelaporan telah diselesaikan.' : 'Pelaksanaan berjalan sesuai SPT terakhir.',
                    'follow_up' => $progress === 100 ? 'Arsipkan dokumen hasil pengawasan.' : 'Pantau penyelesaian laporan dan tindak lanjut.',
                ]);
            }
        }
    }
}
