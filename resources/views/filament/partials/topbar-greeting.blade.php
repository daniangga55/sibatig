@php($user = filament()->auth()->user())
@php($contextLabel = request()->routeIs('filament.admin.pages.kalender-kegiatan') ? 'Kalender Kegiatan' : $user?->name)

@if ($user)
    <div class="sibatig-topbar-greeting">
        <span>Selamat datang kembali,</span>
        <strong>{{ $contextLabel }}</strong>
    </div>
@endif
