<?php

namespace App\Filament\Resources\SptRecords\Pages\Concerns;

use Filament\Notifications\Notification;

trait HasSequentialSptTabs
{
    public int $activeSptTab = 1;

    public int $highestAccessibleSptTab = 1;

    public function canAccessSptTab(int $tab): bool
    {
        return $tab >= 1 && $tab <= $this->highestAccessibleSptTab;
    }

    public function advanceSptTab(int $currentTab): void
    {
        if ($currentTab !== $this->activeSptTab || ! in_array($currentTab, [1, 2], true)) {
            return;
        }

        $missingFields = $this->missingRequiredFieldsForSptTab($currentTab);

        if ($missingFields !== []) {
            Notification::make()
                ->title("Lengkapi tab {$currentTab} terlebih dahulu")
                ->body('Kolom wajib: '.implode(', ', $missingFields).'.')
                ->danger()
                ->send();

            return;
        }

        $nextTab = $currentTab + 1;
        $this->highestAccessibleSptTab = max($this->highestAccessibleSptTab, $nextTab);
        $this->activeSptTab = $nextTab;
        $this->dispatch('spt-tab-changed');
    }

    public function returnToSptTab(int $tab): void
    {
        if (! $this->canAccessSptTab($tab)) {
            return;
        }

        $this->activeSptTab = $tab;
        $this->dispatch('spt-tab-changed');
    }

    public function updatedActiveSptTab(int|string $tab): void
    {
        $requestedTab = (int) $tab;

        $this->activeSptTab = $this->canAccessSptTab($requestedTab)
            ? $requestedTab
            : $this->highestAccessibleSptTab;
    }

    /**
     * @return array<string>
     */
    private function missingRequiredFieldsForSptTab(int $tab): array
    {
        $state = $this->form->getRawState();
        $requiredFields = match ($tab) {
            1 => [
                'year' => 'Tahun',
                'source_number' => 'Nomor urut',
                'document_number' => 'Nomor SPT',
                'document_date' => 'Tanggal SPT',
                'start_date' => 'Mulai pelaksanaan',
                'subject' => 'Uraian penugasan',
            ],
            2 => [
                'relation_type' => 'Relasi',
                'assignment_type' => 'Jenis penugasan',
                'status' => 'Status',
            ],
            default => [],
        };

        return collect($requiredFields)
            ->filter(fn (string $label, string $field): bool => blank($state[$field] ?? null))
            ->values()
            ->all();
    }
}
