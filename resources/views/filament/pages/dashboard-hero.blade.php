<div class="sibatig-dashboard">
    <section class="sibatig-hero">
        <div class="sibatig-hero-content">
            <span class="sibatig-eyebrow"><i></i> Data terintegrasi PKPT&ndash;Monitoring</span>
            <h1>Kinerja pengawasan,<br><em>dalam satu kendali.</em></h1>
            <p>Pantau realisasi {{ $total }} kegiatan PKPT {{ $year }} secara cepat, terukur, dan terdokumentasi.</p>
            <div class="sibatig-hero-actions">
                <a href="{{ $pkptUrl }}" wire:navigate>Lihat status PKPT <span>&rarr;</span></a>
                <a href="{{ $monitoringUrl }}" wire:navigate>Buka monitoring</a>
            </div>
        </div>
        <div class="sibatig-hero-visual" aria-hidden="true">
            <i class="sibatig-orb first"></i><i class="sibatig-orb second"></i>
            <div class="sibatig-mini-dashboard">
                <div class="sibatig-mini-top"><span></span><span></span><span></span><b>PKPT {{ $year }}</b></div>
                <div class="sibatig-mini-body">
                    <div class="sibatig-mini-ring" style="--coverage: {{ $coverage }}"><span>{{ $coverage }}<small>%</small></span></div>
                    <div class="sibatig-mini-bars">
                        @foreach ([28, 42, 52, 70, 84] as $height)
                            <i style="--bar-height: {{ $height }}%"></i>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="sibatig-section-heading">
        <div><span>IKHTISAR PKPT &amp; MONITORING</span><h2>Kondisi terkini</h2></div>
        <p>Data diperbarui otomatis dari monitoring terakhir</p>
    </div>

    <section class="sibatig-stats-grid" aria-label="Ringkasan PKPT {{ $year }}">
        <a class="sibatig-stat-card total" href="{{ $pkptUrl }}" wire:navigate>
            <div class="sibatig-stat-head">
                <span class="sibatig-stat-icon"><svg viewBox="0 0 24 24"><path d="M5 3h14v18H5V3Zm3 4v2h8V7H8Zm0 4v2h8v-2H8Zm0 4v2h5v-2H8Z"/></svg></span>
                <span class="sibatig-trend">PKPT {{ $year }}</span>
            </div>
            <p>Total kegiatan</p><div><strong>{{ $total }}</strong><span>Penugasan PKPT</span></div>
        </a>
        <a class="sibatig-stat-card pending" href="{{ $pkptUrl }}" wire:navigate>
            <div class="sibatig-stat-head">
                <span class="sibatig-stat-icon"><svg viewBox="0 0 24 24"><path d="M12 2a10 10 0 1 0 0 20 10 10 0 0 0 0-20Zm1 5v5.6l3.5 2.1-1 1.7-4.5-2.8V7h2Z"/></svg></span>
                <span class="sibatig-trend">{{ 100 - $coverage }}%</span>
            </div>
            <p>Belum dimonitoring</p><div><strong>{{ $activitiesWithoutMonitoring }}</strong><span>Kegiatan PKPT</span></div>
        </a>
        <a class="sibatig-stat-card progress" href="{{ $monitoringUrl }}" wire:navigate>
            <div class="sibatig-stat-head">
                <span class="sibatig-stat-icon"><svg viewBox="0 0 24 24"><path d="M12 2v10h10A10 10 0 1 1 12 2Zm2 0a10 10 0 0 1 8 8h-8V2Z"/></svg></span>
                <span class="sibatig-trend up">{{ $coverage }}%</span>
            </div>
            <p>Sudah dimonitoring</p><div><strong>{{ $started }}</strong><span>Dari {{ $total }} kegiatan</span></div>
        </a>
        <a class="sibatig-stat-card complete" href="{{ $monitoringUrl }}" wire:navigate>
            <div class="sibatig-stat-head">
                <span class="sibatig-stat-icon"><svg viewBox="0 0 24 24"><path d="M9 16.2 4.8 12l-1.4 1.4L9 19 21 7l-1.4-1.4L9 16.2Z"/></svg></span>
                <span class="sibatig-trend up">Terverifikasi</span>
            </div>
            <p>Kegiatan selesai</p><div><strong>{{ $completed }}</strong><span>Realisasi PKPT</span></div>
        </a>
    </section>

    <section class="sibatig-dashboard-grid">
        <article class="sibatig-panel sibatig-progress-panel">
            <header class="sibatig-panel-header">
                <div><p>CAKUPAN MONITORING</p><h3>Realisasi PKPT {{ $year }}</h3></div>
                <a href="{{ $pkptUrl }}" wire:navigate>Lihat detail</a>
            </header>
            <div class="sibatig-progress-overview">
                <div class="sibatig-donut" style="--value: {{ $coverage }}"><div><strong>{{ $coverage }}%</strong><span>Dimonitoring</span></div></div>
                <div class="sibatig-progress-summary">
                    <strong>{{ $started }} dari {{ $total }} kegiatan</strong>
                    <p>sudah memiliki catatan monitoring</p>
                    <div class="sibatig-legend">
                        <span><i class="is-blue"></i>Sudah dimonitoring <b>{{ $coverage }}%</b></span>
                        <span><i class="is-light"></i>Belum dimonitoring <b>{{ 100 - $coverage }}%</b></span>
                    </div>
                </div>
            </div>
            <div class="sibatig-monthly-chart" aria-label="Grafik progres monitoring per bulan">
                @foreach ($monthlyCoverage as $month)
                    <div class="{{ $loop->last ? 'current' : '' }}">
                        <span class="sibatig-bar-value">{{ $month['percentage'] }}%</span>
                        <i style="--bar-value: {{ max($month['percentage'], 4) }}%"></i>
                        <small>{{ $month['label'] }}</small>
                    </div>
                @endforeach
            </div>
        </article>

        <article class="sibatig-panel sibatig-composition-panel">
            <header class="sibatig-panel-header">
                <div><p>KOMPOSISI PROGRAM</p><h3>PKPT berdasarkan jenis</h3></div>
                <a href="{{ $pkptUrl }}" wire:navigate>{{ $total }} kegiatan</a>
            </header>
            <div class="sibatig-composition-list">
                @foreach (['audit' => 'Audit', 'reviu' => 'Reviu', 'monitoring' => 'Monitoring', 'evaluasi' => 'Evaluasi', 'pendampingan' => 'Pendampingan', 'mandatory' => 'Mandatory'] as $category => $label)
                    @php
                        $count = $categoryCounts[$category] ?? 0;
                        $percentage = $total > 0 ? round(($count / $total) * 100, 1) : 0;
                    @endphp
                    <div class="sibatig-composition-row">
                        <span><i class="{{ $category }}"></i>{{ $label }} <b>{{ $count }}</b></span>
                        <div><i class="{{ $category }}" style="width: {{ $percentage }}%"></i></div>
                        <small>{{ number_format($percentage, 1, ',', '.') }}%</small>
                    </div>
                @endforeach
            </div>
            @php
                $largestCategory = collect($categoryCounts)->sortDesc()->keys()->first() ?? 'reviu';
                $categoryLabels = ['audit' => 'Audit', 'reviu' => 'Reviu', 'monitoring' => 'Monitoring', 'evaluasi' => 'Evaluasi', 'pendampingan' => 'Pendampingan', 'mandatory' => 'Mandatory'];
            @endphp
            <footer class="sibatig-composition-footer"><span>Kelompok terbesar</span><strong>{{ $categoryLabels[$largestCategory] ?? ucfirst($largestCategory) }} &middot; {{ $categoryCounts[$largestCategory] ?? 0 }} kegiatan</strong></footer>
        </article>
    </section>

    <article class="sibatig-panel sibatig-activity-panel">
        <header class="sibatig-panel-header">
            <div><p>INTEGRASI PKPT&ndash;MONITORING</p><h3>Realisasi terbaru</h3></div>
            <a href="{{ $monitoringUrl }}" wire:navigate>Lihat semua</a>
        </header>
        <div class="sibatig-table-wrap">
            <table>
                <thead><tr><th>Kegiatan PKPT</th><th>Jenis</th><th>Monitoring terakhir</th><th>Progres</th><th>Status</th><th></th></tr></thead>
                <tbody>
                    @forelse ($latestMonitoring as $activity)
                        @php $evaluation = $activity->monitoringEvaluations->first(); @endphp
                        <tr>
                            <td><div class="sibatig-activity-name"><span>{{ $activity->source_number }}</span><div><strong>{{ $activity->assignment }}</strong><small>PKPT No. {{ $activity->source_number }} &middot; {{ $activity->audit_object ?: 'Irban Tiga' }}</small></div></div></td>
                            <td><span class="sibatig-type-badge {{ $activity->category->value }}">{{ $activity->category->label() }}</span></td>
                            <td><strong>{{ $evaluation?->evaluation_date?->locale('id')->translatedFormat('d M Y') }}</strong><small>{{ $evaluation?->stage ?: 'Pembaruan monitoring' }}</small></td>
                            <td><div class="sibatig-progress-cell"><span><i style="width: {{ $activity->progress }}%"></i></span><b>{{ $activity->progress }}%</b></div></td>
                            <td><span class="sibatig-status {{ $activity->status->color() }}"><i></i>{{ $activity->status->label() }}</span></td>
                            <td><a class="sibatig-row-arrow" href="{{ $pkptUrl }}/{{ $activity->getRouteKey() }}" wire:navigate aria-label="Lihat PKPT {{ $activity->source_number }}">&rarr;</a></td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="sibatig-empty">Belum ada data monitoring untuk PKPT {{ $year }}.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <footer class="sibatig-panel-footer"><span>Menampilkan <strong>{{ $latestMonitoring->count() }}</strong> pembaruan terakhir</span><a href="{{ $monitoringUrl }}" wire:navigate>Lihat seluruh monitoring <span>&rarr;</span></a></footer>
    </article>
</div>
