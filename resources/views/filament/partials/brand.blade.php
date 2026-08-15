@php($settings = rescue(fn () => \App\Models\WebsiteSetting::current(), null, report: false))

<div class="sibatig-brand">
    <span class="sibatig-brand-mark" aria-hidden="true">
        @if ($settings?->logo_path)
            <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($settings->logo_path) }}" alt="">
        @else
            <svg viewBox="0 0 40 40"><path d="M11 12.5h7v18h-7zM22 8h7v22.5h-7z"/><path class="sibatig-brand-accent" d="M8 33h24v3H8zM12 8l8-4 8 4v2H12z"/></svg>
        @endif
    </span>
    <span class="sibatig-brand-copy">
        <strong>{{ $settings?->site_name ?? 'SIBATIG' }}</strong>
        <small>Irban Tiga</small>
    </span>
</div>
