@extends('public.layout')

@section('title', 'Ketentuan Layanan | SIBATIG Irban 3')
@section('description', 'Ketentuan penggunaan aplikasi SIBATIG Irban 3 Inspektorat Kota Kediri.')

@section('content')
    <section class="legal-hero">
        <div class="public-container legal-heading">
            <span class="public-eyebrow">Ketentuan Layanan</span>
            <h1>Ketentuan penggunaan SIBATIG</h1>
            <p>Berlaku sejak 24 Agustus 2026 &middot; Terakhir diperbarui 24 Agustus 2026</p>
        </div>
    </section>

    <section class="public-container legal-single">
        <article class="legal-content">
            <section><h2>1. Ruang lingkup</h2><p>SIBATIG disediakan oleh {{ config('sibatig.organization') }} untuk mendukung pengelolaan kegiatan pengawasan Irban 3. Penggunaan panel terbatas pada akun yang telah diberi kewenangan.</p></section>
            <section><h2>2. Akun dan keamanan</h2><p>Pengguna wajib menjaga kerahasiaan kredensial, menggunakan akun sesuai kewenangan, dan segera melaporkan dugaan penyalahgunaan. Aktivitas yang dilakukan melalui akun pengguna menjadi tanggung jawab pemilik akun sesuai kebijakan organisasi.</p></section>
            <section><h2>3. Penggunaan yang diperbolehkan</h2><p>Aplikasi hanya boleh digunakan untuk pekerjaan resmi dan tujuan yang sah. Pengguna dilarang mencoba mengakses data tanpa kewenangan, mengganggu layanan, mengunggah materi berbahaya, atau menggunakan aplikasi untuk tujuan yang bertentangan dengan peraturan.</p></section>
            <section><h2>4. Integrasi Google Drive</h2><p>Integrasi Google Drive digunakan sebagai penyimpanan file aplikasi. Pengguna yang melakukan otorisasi menyatakan memiliki kewenangan atas akun dan folder yang digunakan. Izin dapat dicabut melalui akun Google, tetapi pencabutan dapat menghentikan fungsi unggah dan unduh dokumen.</p></section>
            <section><h2>5. Ketersediaan dan perubahan</h2><p>Layanan dapat dihentikan sementara untuk pemeliharaan, keamanan, atau keadaan di luar kendali pengelola. Fitur dan ketentuan dapat diperbarui untuk menyesuaikan kebutuhan organisasi dan ketentuan yang berlaku.</p></section>
            <section><h2>6. Privasi</h2><p>Pengolahan data dijelaskan dalam <a href="{{ route('privacy-policy') }}">Kebijakan Privasi SIBATIG</a>, yang merupakan bagian dari ketentuan ini.</p></section>
            <section><h2>7. Kontak</h2><p>Pertanyaan terkait layanan dapat dikirim ke <a href="mailto:{{ config('sibatig.contact_email') }}">{{ config('sibatig.contact_email') }}</a>.</p></section>
        </article>
    </section>
@endsection
