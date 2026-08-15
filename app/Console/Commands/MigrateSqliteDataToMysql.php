<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Database\ConnectionInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use RuntimeException;
use Throwable;

class MigrateSqliteDataToMysql extends Command
{
    protected $signature = 'sibatig:migrate-sqlite-to-mysql
        {--source= : Lokasi absolut database SQLite sumber}
        {--target=mysql : Nama koneksi MySQL target}
        {--dry-run : Hanya audit tabel dan jumlah data tanpa menyalin}';

    protected $description = 'Menyalin seluruh data SIBATIG dari SQLite ke database MySQL kosong tanpa menghapus sumber';

    /**
     * Tabel disusun berdasarkan ketergantungan foreign key.
     * Tabel migrations tidak disalin karena dibuat oleh `artisan migrate` pada target.
     *
     * @var array<int, string>
     */
    private array $tables = [
        'users',
        'password_reset_tokens',
        'sessions',
        'cache',
        'cache_locks',
        'jobs',
        'job_batches',
        'failed_jobs',
        'website_settings',
        'team_members',
        'pkpt_activities',
        'pkpt_activity_team_member',
        'monitoring_evaluations',
        'spt_records',
        'documents',
    ];

    public function handle(): int
    {
        $sourcePath = $this->option('source') ?: database_path('database.sqlite');
        $targetName = (string) $this->option('target');

        if (! is_file($sourcePath)) {
            $this->error("Database SQLite sumber tidak ditemukan: {$sourcePath}");

            return self::FAILURE;
        }

        if (config("database.connections.{$targetName}.driver") !== 'mysql') {
            $this->error("Koneksi target [{$targetName}] bukan MySQL.");

            return self::FAILURE;
        }

        config(['database.connections.sqlite_legacy.database' => $sourcePath]);
        DB::purge('sqlite_legacy');
        DB::purge($targetName);

        try {
            $source = DB::connection('sqlite_legacy');
            $target = DB::connection($targetName);
            $tables = $this->availableTables($source, $target, $targetName);
            $sourceCounts = $this->tableCounts($source, $tables);

            $this->table(
                ['Tabel', 'Baris SQLite'],
                collect($sourceCounts)->map(fn (int $count, string $table): array => [$table, $count])->values()->all(),
            );

            if ((bool) $this->option('dry-run')) {
                $this->info('Audit selesai. Tidak ada data yang diubah.');

                return self::SUCCESS;
            }

            $occupiedTables = collect($this->tableCounts($target, $tables))
                ->filter(fn (int $count): bool => $count > 0);

            if ($occupiedTables->isNotEmpty()) {
                $details = $occupiedTables
                    ->map(fn (int $count, string $table): string => "{$table} ({$count})")
                    ->implode(', ');

                throw new RuntimeException("Migrasi dibatalkan karena target sudah berisi data: {$details}.");
            }

            $target->statement('SET FOREIGN_KEY_CHECKS=0');

            try {
                $target->transaction(function () use ($source, $target, $tables): void {
                    foreach ($tables as $table) {
                        $rows = $source->table($table)
                            ->get()
                            ->map(fn (object $row): array => (array) $row)
                            ->all();

                        foreach (array_chunk($rows, 250) as $chunk) {
                            $target->table($table)->insert($chunk);
                        }
                    }
                });
            } finally {
                $target->statement('SET FOREIGN_KEY_CHECKS=1');
            }

            $targetCounts = $this->tableCounts($target, $tables);
            $mismatches = collect($sourceCounts)
                ->filter(fn (int $count, string $table): bool => $targetCounts[$table] !== $count);

            if ($mismatches->isNotEmpty()) {
                throw new RuntimeException('Verifikasi jumlah baris gagal pada: '.$mismatches->keys()->implode(', ').'.');
            }

            $this->newLine();
            $this->info('Migrasi data berhasil. SQLite sumber tetap utuh dan seluruh jumlah baris cocok.');

            return self::SUCCESS;
        } catch (Throwable $exception) {
            report($exception);
            $this->error($exception->getMessage());

            return self::FAILURE;
        } finally {
            DB::disconnect('sqlite_legacy');
            DB::disconnect($targetName);
        }
    }

    /**
     * @return array<int, string>
     */
    private function availableTables(
        ConnectionInterface $source,
        ConnectionInterface $target,
        string $targetName,
    ): array {
        return collect($this->tables)
            ->filter(function (string $table) use ($source, $target, $targetName): bool {
                if (! Schema::connection('sqlite_legacy')->hasTable($table)) {
                    return false;
                }

                if (! Schema::connection($targetName)->hasTable($table)) {
                    throw new RuntimeException("Tabel target belum tersedia: {$table}.");
                }

                $source->table($table)->limit(1)->get();
                $target->table($table)->limit(1)->get();

                return true;
            })
            ->values()
            ->all();
    }

    /**
     * @param  array<int, string>  $tables
     * @return array<string, int>
     */
    private function tableCounts(ConnectionInterface $connection, array $tables): array
    {
        return collect($tables)
            ->mapWithKeys(fn (string $table): array => [$table => $connection->table($table)->count()])
            ->all();
    }
}
