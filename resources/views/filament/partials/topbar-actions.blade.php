@php
    $user = filament()->auth()->user();
    $attentionCount = rescue(fn (): int => \App\Support\SibatigMetrics::get('pkpt_in_progress'), 0, report: false);
    $roleLabel = $user?->teamMember?->position ?? $user?->role?->label() ?? 'Pengguna';
@endphp

@if ($user)
    <a
        href="{{ \App\Filament\Resources\MonitoringEvaluations\MonitoringEvaluationResource::getUrl('index') }}"
        wire:navigate
        class="sibatig-topbar-notification"
        aria-label="{{ $attentionCount }} kegiatan memerlukan perhatian"
    >
        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 22a2.5 2.5 0 0 0 2.4-2H9.6a2.5 2.5 0 0 0 2.4 2ZM5 17h14l-2-3v-4a5 5 0 0 0-4-4.9V3h-2v2.1A5 5 0 0 0 7 10v4l-2 3Z"/></svg>
        @if ($attentionCount > 0)
            <span>{{ $attentionCount }}</span>
        @endif
    </a>

    <button
        type="button"
        class="sibatig-topbar-profile-copy"
        x-on:click="$el.closest('.fi-topbar-end').querySelector('.fi-user-menu-trigger')?.click()"
        aria-label="Buka menu profil {{ $user->name }}"
    >
        <span><strong>{{ $user->name }}</strong><small>{{ $roleLabel }}</small></span>
        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="m7 9 5 5 5-5H7Z"/></svg>
    </button>
@endif
