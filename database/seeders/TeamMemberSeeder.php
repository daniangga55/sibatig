<?php

namespace Database\Seeders;

use App\Models\TeamMember;
use Illuminate\Database\Seeder;

class TeamMemberSeeder extends Seeder
{
    public function run(): void
    {
        $members = [
            ['full_name' => 'Dedi Haryadi, S.H., M.M.', 'nip' => '197312092001121004', 'rank' => 'Pembina Tk. I', 'grade' => 'IV/b', 'position' => 'Inspektur Pembantu III', 'is_leader' => true],
            ['full_name' => 'Sri Mulyani, S.E., M.M.', 'nip' => '197408041999012001', 'rank' => 'Pembina Tk. I', 'grade' => 'IV/b', 'position' => 'PPUPD Ahli Madya'],
            ['full_name' => 'Bram Brahmana, S.T.', 'nip' => '198605172010011009', 'rank' => 'Penata Tk. I', 'grade' => 'III/d', 'position' => 'Auditor Ahli Muda'],
            ['full_name' => 'Dwi Yunianto, S.E., M.M.', 'nip' => '197601242001121004', 'rank' => 'Pembina Utama Muda', 'grade' => 'IV/c', 'position' => 'PPUPD Ahli Madya'],
            ['full_name' => 'Wawan Wicaksono, S.T.', 'nip' => '197911102001121002', 'rank' => 'Penata Tk. I', 'grade' => 'III/d', 'position' => 'PPUPD Ahli Muda'],
            ['full_name' => 'Anik Kusmianingsih, S.Pd. Kim.', 'nip' => '197912102009012004', 'rank' => 'Penata', 'grade' => 'III/c', 'position' => 'Auditor Ahli Muda'],
            ['full_name' => 'Dani Angga Setyantono, S.T.', 'nip' => '199706292025041001', 'rank' => 'Penata Muda', 'grade' => 'III/a', 'position' => 'Auditor Ahli Pertama'],
        ];

        foreach ($members as $index => $member) {
            TeamMember::query()->firstOrCreate(
                ['nip' => $member['nip']],
                [...$member, 'is_active' => true, 'sort_order' => $index + 1],
            );
        }
    }
}
