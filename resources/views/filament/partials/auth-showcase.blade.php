@if (filament()->auth()->guest())
    <section class="sibatig-auth-showcase" aria-label="Tentang SIBATIG">
        <div class="sibatig-auth-brand">
            @include('filament.partials.brand')
        </div>
        <div class="sibatig-auth-message">
            <span class="sibatig-eyebrow"><i></i> Sistem pengawasan terintegrasi</span>
            <h1>Kinerja pengawasan,<br><em>dalam satu kendali.</em></h1>
            <p>Kelola PKPT, tim, monitoring, dan evaluasi Irban Tiga secara cepat, terukur, dan terdokumentasi.</p>
            <div class="sibatig-auth-benefits">
                <div><b>✓</b><span><strong>Data terpusat</strong><small>Satu sumber data yang mudah ditelusuri</small></span></div>
                <div><b>↗</b><span><strong>Monitoring aktual</strong><small>Progres kegiatan selalu dapat dipantau</small></span></div>
                <div><b>⌾</b><span><strong>Akses aman</strong><small>Hak akses disesuaikan dengan peran</small></span></div>
            </div>
        </div>
        <footer>Integritas · Kompeten · Profesional</footer>
        <i class="sibatig-auth-orb one"></i><i class="sibatig-auth-orb two"></i>
    </section>
@endif
