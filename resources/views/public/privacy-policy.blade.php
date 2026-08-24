@extends('public.layout')

@section('title', 'Kebijakan Privasi | SIBATIG Irban 3')
@section('description', 'Kebijakan Privasi SIBATIG menjelaskan akses, penggunaan, penyimpanan, pembagian, retensi, dan penghapusan data pengguna serta data Google Drive.')

@section('content')
    <section class="legal-hero">
        <div class="public-container legal-heading">
            <span class="public-eyebrow">Kebijakan Privasi</span>
            <h1>Privasi dan keamanan data SIBATIG</h1>
            <p>Berlaku sejak 24 Agustus 2026 &middot; Terakhir diperbarui 24 Agustus 2026</p>
        </div>
    </section>

    <section class="public-container legal-layout">
        <aside aria-label="Daftar isi kebijakan">
            <strong>Daftar isi</strong>
            <a href="#pengelola">Pengelola</a>
            <a href="#data">Data yang diproses</a>
            <a href="#google">Data pengguna Google</a>
            <a href="#penyimpanan">Penyimpanan dan keamanan</a>
            <a href="#retensi">Retensi dan penghapusan</a>
            <a href="#hak">Hak pengguna</a>
            <a href="#kontak">Kontak</a>
        </aside>
        <article class="legal-content">
            <section id="pengelola">
                <h2>1. Pengelola aplikasi</h2>
                <p>SIBATIG dikelola oleh {{ config('sibatig.organization') }} untuk mendukung administrasi dan pengawasan Irban 3. Kebijakan ini menjelaskan bagaimana aplikasi mengakses, menggunakan, menyimpan, membagikan, mempertahankan, dan menghapus data.</p>
            </section>

            <section id="data">
                <h2>2. Data yang diproses</h2>
                <p>Aplikasi dapat memproses identitas akun kerja, peran pengguna, data tim, PKPT, SPT, monitoring, evaluasi, jadwal, serta dokumen yang diunggah oleh pengguna berwenang. Log teknis terbatas dapat digunakan untuk keamanan, audit, dan diagnosis gangguan.</p>
            </section>

            <section id="google">
                <h2>3. Data pengguna Google</h2>
                <p>SIBATIG menggunakan OAuth 2.0 dan scope <code>https://www.googleapis.com/auth/drive.file</code>. Akses ini digunakan hanya untuk membuat folder SIBATIG serta mengunggah, membaca, mengganti, dan menghapus file yang dibuat atau dikelola aplikasi berdasarkan tindakan pengguna berwenang.</p>
                <ul>
                    <li>SIBATIG tidak membaca seluruh isi Google Drive pengguna.</li>
                    <li>Data Google tidak digunakan untuk iklan, pembuatan profil pemasaran, atau dijual.</li>
                    <li>Data tidak dibagikan kepada pihak ketiga, kecuali penyedia infrastruktur yang diperlukan untuk menjalankan layanan, atas instruksi pengguna, atau jika diwajibkan oleh hukum.</li>
                    <li>Refresh token digunakan agar server dapat menjalankan operasi file yang diminta tanpa meminta persetujuan ulang pada setiap operasi.</li>
                </ul>
                <div class="legal-callout">
                    Penggunaan dan transfer informasi yang diterima SIBATIG dari Google API mematuhi <a href="https://developers.google.com/terms/api-services-user-data-policy" target="_blank" rel="noopener noreferrer">Google API Services User Data Policy</a>, termasuk persyaratan Limited Use.
                </div>
                <h3>Google User Data Disclosure (English)</h3>
                <p>SIBATIG uses the Google Drive <code>drive.file</code> scope solely to create and manage application files in the SIBATIG folder. Google user data is not sold, used for advertising, or transferred for unrelated purposes. Its use complies with the Google API Services User Data Policy, including the Limited Use requirements.</p>
            </section>

            <section id="penyimpanan">
                <h2>4. Penyimpanan dan keamanan</h2>
                <p>Metadata dokumen disimpan pada database aplikasi. File dapat disimpan pada Google Drive yang telah diotorisasi. Kredensial dan token disimpan di sisi server, tidak dimasukkan ke repository publik, dan akses aplikasi dibatasi berdasarkan peran. Deployment production menggunakan HTTPS serta kontrol akses server.</p>
            </section>

            <section id="retensi">
                <h2>5. Retensi dan penghapusan</h2>
                <p>Data dipertahankan selama diperlukan untuk pelaksanaan tugas pengawasan, pemenuhan ketentuan arsip, keamanan, dan kewajiban hukum organisasi. Pengguna berwenang dapat menghapus dokumen melalui aplikasi sesuai kebijakan organisasi. Penghapusan permanen akan menghapus file terkait dari storage yang digunakan.</p>
                <p>Permintaan penghapusan data atau pemutusan integrasi dapat dikirim melalui alamat kontak di bawah. Izin Google juga dapat dicabut melalui <a href="https://myaccount.google.com/connections" target="_blank" rel="noopener noreferrer">halaman koneksi akun Google</a>.</p>
            </section>

            <section id="hak">
                <h2>6. Hak dan pilihan pengguna</h2>
                <p>Pengguna dapat meminta informasi, koreksi, pembatasan, atau penghapusan data sesuai kewenangan dan ketentuan yang berlaku. Pencabutan akses Google menghentikan operasi baru pada Google Drive, tetapi tidak otomatis menghapus catatan yang wajib dipertahankan berdasarkan kebijakan arsip.</p>
            </section>

            <section>
                <h2>7. Perubahan kebijakan</h2>
                <p>Kebijakan ini dapat diperbarui ketika fungsi aplikasi atau ketentuan yang berlaku berubah. Tanggal pembaruan terbaru akan ditampilkan pada halaman ini.</p>
            </section>

            <section id="kontak">
                <h2>8. Kontak privasi</h2>
                <p>Pertanyaan atau permintaan terkait privasi dapat dikirim ke <a href="mailto:{{ config('sibatig.contact_email') }}">{{ config('sibatig.contact_email') }}</a>.</p>
            </section>
        </article>
    </section>
@endsection
