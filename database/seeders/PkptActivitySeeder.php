<?php

namespace Database\Seeders;

use App\Models\PkptActivity;
use App\Models\TeamMember;
use Illuminate\Database\Seeder;

class PkptActivitySeeder extends Seeder
{
    public function run(): void
    {
        $activities = [
            [1, 'audit', 'Audit Kinerja', 'Audit Program pengelolaan dan pengembangan sistem drainase', 'DPUPR, Dinas Perkim', 'IRBAN III', 7],
            [4, 'audit', 'Audit Ketaatan', 'Audit Program perencanaan, pengendalian dan evaluasi pembangunan daerah', 'Badan Perencanaan Pembangunan Daerah', 'IRBAN III', 20],
            [7, 'audit', 'Audit Dengan Tujuan Tertentu', 'Audit Pekerjaan Fisik Proyek Strategis', 'DPUPR', 'IRBAN III', 7],
            [8, 'audit', 'Audit Dengan Tujuan Tertentu', 'Audit Program Pemberdayaan Masyarakat Desa dan Kelurahan', 'Kecamatan Kota', 'IRBAN III', 7],
            [9, 'audit', 'Probity Audit', 'Program/Kegiatan sebagaimana yang tercantum dalam SK Walikota tentang Proyek Strategis Pemerintah Kota Kediri Tahun 2026', 'OPD dalam SK Proyek Strategis Pemerintah Kota Kediri Tahun 2026', 'IRBAN I, II, III', 21],
            [13, 'reviu', 'Reviu', 'Reviu RKA Tahun 2027', 'Pemerintah Daerah Kota Kediri', 'IRBAN I, II, III', 21],
            [14, 'reviu', 'Reviu', 'Reviu RKA Perubahan Tahun 2026', 'Pemerintah Daerah Kota Kediri', 'IRBAN I, II, III', 21],
            [21, 'reviu', 'Reviu', 'Reviu Register Risiko Tahun 2026', 'Pemerintah Daerah Kota Kediri', 'IRBAN I, II, III', 21],
            [22, 'reviu', 'Reviu', 'Reviu PKPT Tahun 2026', 'Pemerintah Daerah Kota Kediri', 'IRBAN III', 10],
            [27, 'reviu', 'Reviu', 'Reviu Renja', 'Pemerintah Daerah Kota Kediri', 'IRBAN III', 7],
            [28, 'reviu', 'Reviu', 'Reviu RKPD', 'Pemerintah Daerah Kota Kediri', 'IRBAN III', 7],
            [29, 'reviu', 'Reviu', 'Reviu RKPD-P', 'Pemerintah Daerah Kota Kediri', 'IRBAN III', 7],
            [30, 'reviu', 'Reviu', 'Pemeriksaan Atas Honorarium Tahun Sebelumnya / Reviu Atas Honorarium Tahun Berjalan dengan Nilai Honorarium Tertinggi', 'Pemerintah Daerah Kota Kediri', 'IRBAN III', 7],
            [31, 'reviu', 'Reviu', 'Reviu atas SSH dan ASB', 'Pemerintah Daerah Kota Kediri', 'IRBAN III', 7],
            [32, 'reviu', 'Reviu', 'Reviu DAK', 'Pemerintah Daerah Kota Kediri', 'IRBAN III', 7],
            [33, 'reviu', 'Reviu', 'Reviu E-Audit PBJ', 'Pemerintah Daerah Kota Kediri', 'IRBAN III', 7],
            [34, 'reviu', 'Reviu', 'Reviu HPS', 'Pemerintah Daerah Kota Kediri', 'IRBAN III', 7],
            [36, 'monitoring', 'Monitoring', 'Pendampingan pemantauan Mewujudkan ketahanan pangan nasional', 'DKPP', 'IRBAN III', 7],
            [37, 'monitoring', 'Monitoring', 'Pendampingan pemantauan Optimalisasi Pelaksanaan Pengentasan Kemiskinan dan Penghapusan Kemiskinan Ekstrem', 'Pemerintah Kota Kediri', 'IRBAN I, II, III', 21],
            [39, 'monitoring', 'Monitoring', 'Pendampingan pemantauan Makan Bergizi Gratis', 'Pemerintah Kota Kediri', 'IRBAN I, II, III', 21],
            [44, 'evaluasi', 'Evaluasi', 'Evaluasi Register Risiko Tahun 2025', 'Pemerintah Daerah Kota Kediri', 'IRBAN I, II, III', 21],
            [47, 'evaluasi', 'Evaluasi', 'Evaluasi SAKIP Tahun 2026', 'Pemerintah Daerah Kota Kediri', 'IRBAN I, II, III', 7],
            [48, 'pendampingan', 'Pendampingan', 'Pendampingan Dana BOS Dinas Pendidikan Tahap II Tahun 2025', 'Dinas Pendidikan', 'IRBAN I, II, III', 7],
            [49, 'pendampingan', 'Pendampingan', 'Pendampingan Dana BOS Dinas Pendidikan Tahap I Tahun 2026', 'Dinas Pendidikan', 'IRBAN I, II, III', 7],
            [50, 'mandatory', 'Mandatory', 'Maturitas SPIP', 'Pemerintah Daerah Kota Kediri', 'IRBAN III', 7],
            [51, 'mandatory', 'Mandatory', 'Penilaian Mandiri Kapabilitas APIP', 'Pemerintah Kota Kediri', 'IRBAN III', 7],
            [52, 'mandatory', 'Mandatory', 'Fasilitasi Manajemen Risiko', 'Pemerintah Daerah Kota Kediri', 'IRBAN III', 10],
            [53, 'mandatory', 'Mandatory', 'PPBR Tahun 2027', null, 'IRBAN III', 12],
            [59, 'mandatory', 'Mandatory', 'Penyusunan PKPT 2027', null, 'IRBAN I, II, III', 27],
        ];

        $team = TeamMember::query()->orderBy('sort_order')->pluck('id')->all();

        foreach ($activities as [$number, $category, $type, $assignment, $object, $executor, $apip]) {
            $activity = PkptActivity::query()->firstOrCreate(
                ['year' => 2026, 'source_number' => $number],
                [
                    'category' => $category,
                    'assignment_type' => $type,
                    'assignment' => $assignment,
                    'audit_object' => $object,
                    'executor' => $executor,
                    'apip_count' => $apip,
                ],
            );

            $activity->teamMembers()->syncWithoutDetaching($team);
        }
    }
}
