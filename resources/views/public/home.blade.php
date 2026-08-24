@extends('public.layout')

@section('title', 'SIBATIG Irban 3 | Sistem Pengawasan Terintegrasi')
@section('description', 'SIBATIG membantu Irban 3 mengelola PKPT, SPT, monitoring, evaluasi, kalender kegiatan, dan dokumen pengawasan secara terintegrasi.')

@section('content')
    <section class="public-hero">
        <div class="public-container hero-grid">
            <div class="hero-copy">
                <span class="public-eyebrow">Sistem pengawasan terintegrasi</span>
                <h1>Pengelolaan kegiatan Irban 3 yang tertata dan terdokumentasi.</h1>
                <p>SIBATIG adalah aplikasi web internal {{ config('sibatig.organization') }} untuk mengelola PKPT, Surat Perintah Tugas, tim pemeriksa, monitoring, evaluasi, kalender kegiatan, dan dokumen pendukung dalam satu ruang kerja.</p>
                <div class="hero-actions">
                    <a class="button primary" href="{{ url('/admin/login') }}">Masuk ke SIBATIG</a>
                    <a class="button secondary" href="{{ route('privacy-policy') }}">Pelajari privasi data</a>
                </div>
                <p class="access-note"><span aria-hidden="true">●</span> Akses aplikasi dibatasi untuk pengguna yang diberi kewenangan.</p>
            </div>
            <div class="hero-card" aria-label="Ringkasan fungsi SIBATIG">
                <img src="{{ asset('images/logo-irban-3.jpg') }}?v=20260819" alt="Logo resmi Irban 3" width="463" height="463">
                <div>
                    <span>IRBAN 3</span>
                    <strong>Integritas, Kompeten, Profesional</strong>
                </div>
            </div>
        </div>
    </section>

    <section class="public-section" id="fungsi">
        <div class="public-container">
            <div class="section-heading">
                <span>Fungsi aplikasi</span>
                <h2>Satu sumber informasi kegiatan pengawasan</h2>
                <p>Setiap modul dirancang untuk membantu proses administrasi dan pemantauan Irban 3.</p>
            </div>
            <div class="feature-grid">
                <article><b>01</b><h3>PKPT dan Rekap SPT</h3><p>Mencatat rencana pengawasan dan menghubungkannya dengan Surat Perintah Tugas yang sudah diterbitkan.</p></article>
                <article><b>02</b><h3>Monitoring dan evaluasi</h3><p>Memantau status, progres, jadwal, serta hasil pelaksanaan kegiatan secara konsisten.</p></article>
                <article><b>03</b><h3>Dokumen terpusat</h3><p>Menyimpan file SPT, program kerja, dan laporan dengan akses yang dibatasi sesuai peran pengguna.</p></article>
            </div>
        </div>
    </section>

    <section class="public-section google-data-section" id="data-google">
        <div class="public-container data-grid">
            <div>
                <span class="public-eyebrow">Penggunaan Google Drive</span>
                <h2>Transparan mengenai data Google</h2>
                <p>SIBATIG menggunakan Google Drive hanya sebagai penyimpanan dokumen yang diunggah melalui aplikasi. Aplikasi meminta scope <code>drive.file</code>, sehingga akses dibatasi pada file dan folder yang dibuat atau dikelola oleh SIBATIG.</p>
            </div>
            <ul class="data-list">
                <li><strong>Diakses</strong><span>File pada folder SIBATIG yang dikelola aplikasi.</span></li>
                <li><strong>Digunakan</strong><span>Untuk mengunggah, mengunduh, mengganti, dan menghapus dokumen sesuai tindakan pengguna berwenang.</span></li>
                <li><strong>Tidak dijual</strong><span>Data Google tidak digunakan untuk iklan dan tidak dijual kepada pihak lain.</span></li>
                <li><strong>Dapat dicabut</strong><span>Pengguna dapat mencabut izin melalui pengaturan keamanan akun Google.</span></li>
            </ul>
        </div>
    </section>

    <section class="public-section trust-section">
        <div class="public-container trust-grid">
            <div><strong>Akses berbasis peran</strong><p>Hanya akun aktif yang berwenang yang dapat membuka panel dan dokumen.</p></div>
            <div><strong>Koneksi terlindungi</strong><p>Deployment production wajib menggunakan HTTPS dan secret disimpan di luar source code.</p></div>
            <div><strong>Kebijakan terbuka</strong><p>Penjelasan penggunaan, penyimpanan, retensi, dan penghapusan data tersedia secara publik.</p></div>
        </div>
    </section>
@endsection
