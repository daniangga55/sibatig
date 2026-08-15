@php($settings = rescue(fn () => \App\Models\WebsiteSetting::current(), null, report: false))

<div class="sibatig-sidebar-help">
    <span class="sibatig-help-icon">?</span>
    <span><strong>Butuh bantuan?</strong><small>Panduan penggunaan sistem</small></span>
</div>
<p class="sibatig-version">{{ $settings?->organization_name ?? 'Inspektorat Kota Kediri' }}<br><span>Versi 1.0.0 · {{ $settings?->active_year ?? 2026 }}</span></p>
