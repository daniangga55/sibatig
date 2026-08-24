<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#082f55">
    <meta name="robots" content="index, follow">
    <meta name="description" content="@yield('description', 'SIBATIG adalah sistem pengelolaan kegiatan pengawasan Irban 3 Inspektorat Kota Kediri.')">
    <link rel="canonical" href="{{ url()->current() }}">
    <link rel="icon" type="image/jpeg" href="{{ asset('images/logo-irban-3.jpg') }}?v=20260819">
    <link rel="stylesheet" href="{{ asset('css/sibatig-public.css') }}?v=1">
    <title>@yield('title', 'SIBATIG Irban 3')</title>
</head>
<body>
    <a class="skip-link" href="#main-content">Lewati ke konten utama</a>
    <header class="site-header">
        <div class="public-container header-inner">
            <a class="public-brand" href="{{ route('home') }}" aria-label="SIBATIG Irban 3 - Beranda">
                <img src="{{ asset('images/logo-irban-3.jpg') }}?v=20260819" alt="Logo Irban 3" width="463" height="463">
                <span><strong>SIBATIG</strong><small>Irban Tiga</small></span>
            </a>
            <nav aria-label="Navigasi publik">
                <a href="{{ route('home') }}#fungsi">Fungsi</a>
                <a href="{{ route('home') }}#data-google">Data Google</a>
                <a href="{{ route('privacy-policy') }}">Privasi</a>
                <a class="header-login" href="{{ url('/admin/login') }}">Masuk aplikasi</a>
            </nav>
        </div>
    </header>

    <main id="main-content">
        @yield('content')
    </main>

    <footer class="site-footer">
        <div class="public-container footer-grid">
            <div>
                <strong>SIBATIG Irban 3</strong>
                <p>Sistem pengelolaan kegiatan pengawasan {{ config('sibatig.organization') }}.</p>
            </div>
            <div class="footer-links">
                <a href="{{ route('privacy-policy') }}">Kebijakan Privasi</a>
                <a href="{{ route('terms-of-service') }}">Ketentuan Layanan</a>
                <a href="mailto:{{ config('sibatig.contact_email') }}">{{ config('sibatig.contact_email') }}</a>
            </div>
        </div>
        <div class="public-container footer-bottom">&copy; {{ date('Y') }} {{ config('sibatig.organization') }}. Integritas &middot; Kompeten &middot; Profesional.</div>
    </footer>
</body>
</html>
