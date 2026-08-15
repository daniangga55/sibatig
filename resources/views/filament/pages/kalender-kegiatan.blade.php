<x-filament-panels::page>
    <div class="sibatig-calendar-page">
        <header class="sibatig-calendar-title">
            <div>
                <p>JADWAL SPT TAHUN {{ $year }}</p>
                <h1>Kalender Kegiatan</h1>
                <span>SPT berlangsung pada hari kerja Senin–Jumat, di luar libur nasional dan cuti bersama.</span>
            </div>
            <a href="{{ $sptUrl }}" wire:navigate>Lihat Rekap SPT</a>
        </header>

        <section class="sibatig-calendar-stats" aria-label="Ringkasan SPT {{ $year }}">
            <article><span>Total SPT {{ $year }}</span><strong>{{ $records->count() }}</strong></article>
            <article><span>Selesai</span><strong>{{ $completedCount }}</strong></article>
            <article><span>On progress</span><strong>{{ str_pad((string) $progressCount, 2, '0', STR_PAD_LEFT) }}</strong></article>
            <article><span>Hari kerja {{ $monthLabel }}</span><strong>{{ $workingDayCount }} hari</strong></article>
        </section>

        <section class="sibatig-calendar-shell">
            <header class="sibatig-calendar-toolbar">
                <div class="sibatig-calendar-period">
                    <button type="button" wire:click="previousMonth" aria-label="Bulan sebelumnya">&larr;</button>
                    <span><small>PERIODE KALENDER</small><strong>{{ $monthLabel }}</strong></span>
                    <button type="button" wire:click="nextMonth" aria-label="Bulan berikutnya">&rarr;</button>
                </div>
                <div class="sibatig-calendar-selectors">
                    <label><span>Bulan</span><select wire:model.live.number="month">
                        @foreach ($monthOptions as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </select></label>
                    <label><span>Tahun</span><select wire:model.live.number="year">
                        @foreach ($yearOptions as $optionYear)
                            <option value="{{ $optionYear }}">{{ $optionYear }}</option>
                        @endforeach
                    </select></label>
                </div>
            </header>

            <div class="sibatig-calendar-legend">
                @foreach (['AUDIT' => 'Audit', 'REVIU' => 'Reviu', 'PENDAMPINGAN' => 'Pendampingan', 'MANDATORY' => 'Mandatory'] as $type => $label)
                    <span><i class="{{ strtolower($type) }}"></i>{{ $label }}</span>
                @endforeach
                <span><i class="holiday-national"></i>Libur nasional</span>
                <span><i class="holiday-collective"></i>Cuti bersama</span>
                <a class="sibatig-calendar-source" href="{{ $holidaySourceUrl }}" target="_blank" rel="noopener noreferrer">Sumber: SKB 3 Menteri</a>
            </div>

            <div @class(['sibatig-calendar-layout', 'agenda-hidden' => ! $agendaVisible])>
                <div class="sibatig-calendar-grid-wrap">
                    <div class="sibatig-calendar-grid" role="grid" aria-label="Kalender {{ $monthLabel }}">
                        @foreach ([['label' => 'Sen', 'weekend' => false], ['label' => 'Sel', 'weekend' => false], ['label' => 'Rab', 'weekend' => false], ['label' => 'Kam', 'weekend' => false], ['label' => 'Jum', 'weekend' => false], ['label' => 'Sab', 'weekend' => true], ['label' => 'Min', 'weekend' => true]] as $weekday)
                            <div @class(['sibatig-calendar-weekday', 'weekend' => $weekday['weekend']]) role="columnheader">{{ $weekday['label'] }}</div>
                        @endforeach

                        @foreach ($days as $day)
                            <button
                                type="button"
                                wire:click="selectDate('{{ $day['date'] }}')"
                                @class([
                                    'sibatig-calendar-day',
                                    'outside' => ! $day['currentMonth'],
                                    'today' => $day['today'],
                                    'selected' => $day['selected'],
                                    'weekend' => $day['weekend'],
                                    'holiday-national' => ($day['holiday']['type'] ?? null) === 'national',
                                    'holiday-collective' => ($day['holiday']['type'] ?? null) === 'collective',
                                ])
                                aria-label="{{ $day['date'] }}{{ $day['holiday'] ? ', '.$day['holiday']['name'] : ($day['weekend'] ? ', akhir pekan' : '') }}{{ $day['count'] ? ', '.$day['count'].' SPT' : '' }}"
                                aria-expanded="{{ $day['selected'] ? 'true' : 'false' }}"
                            >
                                <span>{{ $day['day'] }} @if ($day['today'])<small>Hari ini</small>@endif</span>
                                @if ($day['holiday'])
                                    <small class="sibatig-calendar-holiday-name" title="{{ $day['holiday']['name'] }}">{{ \Illuminate\Support\Str::limit($day['holiday']['name'], 26) }}</small>
                                @elseif ($day['weekend'])
                                    <small class="sibatig-calendar-holiday-name">Akhir pekan</small>
                                @endif
                                @if ($day['count'])
                                    <div class="sibatig-calendar-dots">
                                        @foreach ($day['types']->take(4) as $type)
                                            <i class="{{ strtolower($type) }}"></i>
                                        @endforeach
                                    </div>
                                    <b>{{ $day['count'] }} SPT</b>
                                @endif
                            </button>
                        @endforeach
                    </div>
                </div>

                <aside
                    wire:key="calendar-agenda-panel"
                    @class(['sibatig-calendar-agenda', 'is-hidden' => ! $agendaVisible])
                    aria-hidden="{{ $agendaVisible ? 'false' : 'true' }}"
                    @if (! $agendaVisible) inert @endif
                >
                    @if ($agendaVisible)
                    <header>
                        <div><small>AGENDA PEMERIKSAAN</small><strong>SPT pada {{ $selected->locale('id')->translatedFormat('d F Y') }}</strong></div>
                        <span>{{ $selectedRecords->count() }} SPT</span>
                    </header>

                    <div class="sibatig-calendar-agenda-list">
                        @if ($selectedHoliday || $selectedIsWeekend)
                            <div @class(['sibatig-calendar-day-status', 'collective' => ($selectedHoliday['type'] ?? null) === 'collective'])>
                                <i aria-hidden="true">{{ $selectedHoliday ? '●' : '○' }}</i>
                                <strong>{{ $selectedHoliday['name'] ?? 'Akhir pekan' }}</strong>
                                <span>Tidak dijadwalkan pelaksanaan SPT pada hari nonkerja.</span>
                            </div>
                        @else
                        @forelse ($selectedRecords as $record)
                            <a href="{{ \App\Filament\Resources\SptRecords\SptRecordResource::getUrl('view', ['record' => $record]) }}" wire:navigate>
                                <span class="{{ strtolower($record->assignment_type) }}">{{ $record->assignment_type }}</span>
                                <strong>{{ $record->subject }}</strong>
                                <small>{{ $record->document_number }}</small>
                                <small>{{ $record->start_date->locale('id')->translatedFormat('d M') }}–{{ ($record->end_date ?? $record->start_date)->locale('id')->translatedFormat('d M Y') }}</small>
                            </a>
                        @empty
                            <div class="sibatig-calendar-empty">
                                <i><svg viewBox="0 0 24 24"><path d="M12 2a10 10 0 1 0 0 20 10 10 0 0 0 0-20Zm0 2a8 8 0 1 1 0 16 8 8 0 0 1 0-16Z"/></svg></i>
                                <strong>Tidak ada SPT</strong>
                                <span>Tidak terdapat rencana pemeriksaan pada periode ini.</span>
                            </div>
                        @endforelse
                        @endif
                    </div>
                    @endif
                </aside>
            </div>
        </section>
    </div>
</x-filament-panels::page>
