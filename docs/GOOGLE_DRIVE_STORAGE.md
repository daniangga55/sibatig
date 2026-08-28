# Penyimpanan dokumen di Google Drive

Google Drive menyimpan **file dokumen**, sedangkan metadata, kategori, relasi SPT, nama file, ukuran, dan nama disk tetap disimpan di tabel `documents` pada MySQL.

> Jangan menempel Client Secret, access token, refresh token, atau isi JSON OAuth ke dokumen ini maupun Git.

## Status konfigurasi proyek

- Kredensial OAuth dibaca dari `storage/app/credentials/google-drive-oauth.json`.
- Token OAuth disimpan otomatis di `storage/app/credentials/google-drive-token.json`.
- Folder ID disimpan otomatis di `storage/app/credentials/google-drive-folder.json`.
- Kedua file tersebut diabaikan Git melalui `.gitignore`.
- Dokumen tetap menggunakan storage `local` sampai koneksi Google Drive berhasil diuji.

## 1. Konfigurasi Google Cloud

1. Aktifkan **Google Drive API** pada project Google Cloud.
2. Konfigurasikan OAuth consent screen dan tambahkan akun yang dipakai sebagai test user jika aplikasi masih berstatus Testing.
3. Pada OAuth Client bertipe **Web application**, tambahkan Authorized redirect URI berikut:

   ```text
   http://127.0.0.1:8000/admin/google-drive/oauth/callback
   ```

4. Aplikasi akan membuat folder `SIBATIG` secara otomatis setelah otorisasi berhasil.

Dokumen baru disimpan menggunakan struktur berikut (folder tahun dibuat otomatis):

```text
SIBATIG/
|-- PKPT/
|   |-- SPT/{tahun}/
|   |-- KERTAS KERJA/{tahun}/
|   `-- LAPORAN/{tahun}/
`-- NON PKPT/
    |-- SPT/{tahun}/
    |-- KERTAS KERJA/{tahun}/
    `-- LAPORAN/{tahun}/
```

`GOOGLE_DRIVE_FOLDER_ID` menunjuk ke folder root `SIBATIG`, sehingga path yang tersimpan di database dimulai dari `PKPT/...` atau `NON PKPT/...`. File lama tidak dipindahkan atau dihapus; struktur ini berlaku untuk upload baru dan file pengganti.

Client Secret pernah ditempel di dokumen proyek. Rotasi Client Secret di Google Cloud, unduh JSON OAuth yang baru, lalu ganti file lokal `storage/app/credentials/google-drive-oauth.json` sebelum melakukan otorisasi.

## 2. Konfigurasi `.env`

```dotenv
FILESYSTEM_DISK=local
DOCUMENT_FILESYSTEM_DISK=local

GOOGLE_DRIVE_CREDENTIALS_PATH=storage/app/credentials/google-drive-oauth.json
GOOGLE_DRIVE_TOKEN_PATH=storage/app/credentials/google-drive-token.json
GOOGLE_DRIVE_FOLDER_PATH=storage/app/credentials/google-drive-folder.json
GOOGLE_DRIVE_CLIENT_ID=
GOOGLE_DRIVE_CLIENT_SECRET=
GOOGLE_DRIVE_REDIRECT_URI=http://127.0.0.1:8000/admin/google-drive/oauth/callback
GOOGLE_DRIVE_ACCESS_TOKEN=
GOOGLE_DRIVE_REFRESH_TOKEN=
GOOGLE_DRIVE_FOLDER_ID=
GOOGLE_DRIVE_DEBUG=false
GOOGLE_DRIVE_LOG_PAYLOAD=false
```

Client ID dan Client Secret boleh dibiarkan kosong karena aplikasi membacanya dari file JSON. Folder ID juga boleh kosong karena aplikasi akan membuat folder `SIBATIG` dan menyimpan ID-nya secara otomatis.

## 3. Otorisasi akun Google

1. Jalankan aplikasi pada `http://127.0.0.1:8000`.
2. Masuk sebagai Super Admin.
3. Buka URL berikut di browser:

   ```text
   http://127.0.0.1:8000/admin/google-drive/oauth/authorize
   ```

4. Pilih akun Google yang akan menjadi pemilik penyimpanan SIBATIG dan setujui akses.
5. Google mengembalikan browser ke aplikasi. Refresh token serta Folder ID akan disimpan otomatis tanpa ditampilkan.
6. Muat ulang konfigurasi:

   ```powershell
   php artisan optimize:clear
   ```

## 4. Uji koneksi

```powershell
php artisan sibatig:gdrive-check
php artisan sibatig:gdrive-check --write
```

Opsi `--write` membuat satu file pemeriksaan sementara, membacanya, lalu langsung menghapusnya.

## 5. Aktifkan Google Drive

Hanya setelah kedua pemeriksaan berhasil, ubah:

```dotenv
DOCUMENT_FILESYSTEM_DISK=google
```

Kemudian jalankan:

```powershell
php artisan optimize:clear
php artisan optimize
php artisan filament:optimize
```

Dokumen baru akan masuk ke Google Drive. Record lama tetap memakai `storage_disk=local`, sehingga perubahan ini tidak menghapus atau memutus akses ke file yang sudah ada.

## Deployment

- Jangan commit isi `storage/app/credentials`.
- Pada hosting, gunakan environment variables atau secret manager untuk kredensial dan token.
- Tambahkan redirect URI HTTPS domain production ke OAuth Client Google Cloud.
- Folder Drive harus dimiliki atau dibagikan kepada akun yang melakukan otorisasi.
- Cadangkan MySQL dan `storage/app/private/documents` sebelum memigrasikan file lama.

## Deployment aaPanel

Folder `storage/app/credentials` sengaja tidak masuk Git. Upload JSON OAuth yang sudah dirotasi secara manual ke server:

```text
/www/wwwroot/hehe.serat-ulem.my.id/sibatig/storage/app/credentials/google-drive-oauth.json
```

Gunakan konfigurasi production berikut:

```dotenv
APP_URL=https://hehe.serat-ulem.my.id
DOCUMENT_FILESYSTEM_DISK=local
GOOGLE_DRIVE_CREDENTIALS_PATH=storage/app/credentials/google-drive-oauth.json
GOOGLE_DRIVE_TOKEN_PATH=storage/app/credentials/google-drive-token.json
GOOGLE_DRIVE_FOLDER_PATH=storage/app/credentials/google-drive-folder.json
GOOGLE_DRIVE_REDIRECT_URI=https://hehe.serat-ulem.my.id/admin/google-drive/oauth/callback
```

Kemudian jalankan dari root project:

```bash
composer install --no-dev --optimize-autoloader
composer show klytron/laravel-google-drive-filesystem
php artisan package:discover --ansi
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan filament:optimize
```

Pastikan direktori kredensial dapat dibaca dan ditulis oleh user web server karena token dan Folder ID akan dibuat di sana:

```bash
chown -R www:www storage/app/credentials storage/framework bootstrap/cache
chmod 700 storage/app/credentials
chmod 600 storage/app/credentials/google-drive-oauth.json
```

Jika user web server aaPanel bukan `www`, sesuaikan nama user dan group. Setelah login sebagai Super Admin, buka:

```text
https://hehe.serat-ulem.my.id/admin/google-drive/oauth/authorize
```

Sesudah callback berhasil, jalankan ulang `php artisan optimize:clear` lalu `php artisan sibatig:gdrive-check --write`. Ubah `DOCUMENT_FILESYSTEM_DISK=google` hanya setelah pemeriksaan berhasil.

Pesan `Module "fileinfo" is already loaded` berarti ekstensi diaktifkan dua kali pada konfigurasi PHP CLI. Jalankan `php --ini`, cari dua deklarasi `extension=fileinfo`, lalu sisakan satu deklarasi saja. Ekstensi `fileinfo` tetap harus aktif.
