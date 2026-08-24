@php
    $settings = rescue(fn () => \App\Models\WebsiteSetting::current(), null, report: false);
    $logoUrl = asset('images/logo-irban-3.jpg').'?v=20260819';
@endphp

<div class="sibatig-brand">
    <span class="sibatig-brand-mark">
        <img src="{{ $logoUrl }}" alt="Logo Irban 3" width="463" height="463">
    </span>
    <span class="sibatig-brand-copy">
        <strong>{{ $settings?->site_name ?? 'SIBATIG' }}</strong>
        <small>Irban Tiga</small>
    </span>
</div>
