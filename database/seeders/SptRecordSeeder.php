<?php

namespace Database\Seeders;

use App\Models\PkptActivity;
use App\Models\SptRecord;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use RuntimeException;

class SptRecordSeeder extends Seeder
{
    public function run(): void
    {
        $sourcePath = base_path('legacy-static/spt-data.js');

        if (! is_file($sourcePath)) {
            throw new RuntimeException("Sumber data SPT tidak ditemukan: {$sourcePath}");
        }

        $source = file_get_contents($sourcePath);
        preg_match_all('/\{([^{}]+)\}/u', $source ?: '', $recordMatches);

        foreach ($recordMatches[1] as $rawRecord) {
            $record = $this->parseRecord($rawRecord);

            if (! isset($record['no'], $record['number'], $record['date'], $record['start'], $record['subject'])) {
                continue;
            }

            $pkptActivityId = null;

            if (isset($record['pkptNo'])) {
                $pkptActivityId = PkptActivity::query()
                    ->where('year', 2026)
                    ->where('source_number', $record['pkptNo'])
                    ->value('id');
            }

            SptRecord::query()->updateOrCreate(
                ['document_number' => $record['number']],
                [
                    'year' => 2026,
                    'source_number' => $record['no'],
                    'document_date' => $this->parseDate($record['date']),
                    'start_date' => $this->parseDate($record['start']),
                    'end_date' => $this->parseDate($record['end'] ?? null),
                    'report_due_date' => $this->parseDate($record['report'] ?? null),
                    'subject' => $record['subject'],
                    'audit_object' => $record['obrik'] ?? null,
                    'report_number' => $record['lhp'] ?? null,
                    'report_date' => $this->parseDate($record['lhpDate'] ?? null),
                    'assignment_type' => $record['type'] ?? 'LAINNYA',
                    'relation_type' => $record['relation'] ?? 'NON PKPT',
                    'status' => $record['status'] ?? 'SELESAI',
                    'pkpt_activity_id' => $pkptActivityId,
                    'match_type' => $record['match'] ?? null,
                ],
            );
        }
    }

    /** @return array<string, int|string> */
    private function parseRecord(string $rawRecord): array
    {
        preg_match_all("/(\\w+):(?:'((?:\\\\'|[^'])*)'|(\\d+))/u", $rawRecord, $fieldMatches, PREG_SET_ORDER);

        $record = [];

        foreach ($fieldMatches as $match) {
            $record[$match[1]] = $match[2] !== ''
                ? str_replace("\\'", "'", $match[2])
                : (int) $match[3];
        }

        return $record;
    }

    private function parseDate(?string $value): ?string
    {
        if (blank($value)) {
            return null;
        }

        $months = [
            'Jan' => '01', 'Feb' => '02', 'Mar' => '03', 'Apr' => '04',
            'Mei' => '05', 'Jun' => '06', 'Jul' => '07', 'Agu' => '08',
            'Sep' => '09', 'Okt' => '10', 'Nov' => '11', 'Des' => '12',
        ];

        return Carbon::createFromFormat('d m Y', str_replace(array_keys($months), array_values($months), $value))
            ->toDateString();
    }
}
