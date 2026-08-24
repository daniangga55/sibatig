# Penyimpanan dokumen di Google Drive

Integrasi ini hanya memindahkan lokasi **file dokumen**. Metadata, kategori, relasi SPT, nama file, ukuran, dan nama disk tetap disimpan pada tabel `documents` di MySQL.

## 1. Siapkan Google Cloud

1. Buat atau pilih project di Google Cloud Console.
2. Aktifkan **Google Drive API**.
3. Konfigurasikan OAuth consent screen.
4. Buat OAuth Client ID dan simpan Client ID serta Client Secret.
5. Dapatkan refresh token dengan scope `https://www.googleapis.com/auth/drive`.
6. Buat folder khusus, misalnya `SIBATIG`, lalu salin ID folder dari URL Google Drive.

Gunakan akun organisasi khusus aplikasi jika tersedia. Jangan memasukkan Client Secret atau refresh token ke Git.

## 2. Isi `.env`

Biarkan disk dokumen tetap `local` selama penyiapan:

```dotenv
FILESYSTEM_DISK=local
DOCUMENT_FILESYSTEM_DISK=local

GOOGLE_DRIVE_CLIENT_ID=client-id-anda
GOOGLE_DRIVE_CLIENT_SECRET=client-secret-anda
GOOGLE_DRIVE_REDIRECT_URI=http://127.0.0.1
GOOGLE_DRIVE_ACCESS_TOKEN=
GOOGLE_DRIVE_REFRESH_TOKEN=refresh-token-anda
GOOGLE_DRIVE_FOLDER_ID=id-folder-sibatig
GOOGLE_DRIVE_DEBUG=false
GOOGLE_DRIVE_LOG_PAYLOAD=false
```

Refresh token lebih sesuai untuk server dibanding access token sementara karena access token akan kedaluwarsa.

## 3. Migrasi dan uji koneksi

```powershell
php artisan migrate
php artisan optimize:clear
php artisan sibatig:gdrive-check
php artisan sibatig:gdrive-check --write
```

Opsi `--write` membuat satu file pemeriksaan sementara, membacanya, lalu langsung menghapusnya.

## 4. Aktifkan Google Drive

Setelah kedua pemeriksaan berhasil, ubah satu baris berikut:

```dotenv
DOCUMENT_FILESYSTEM_DISK=google
```

Kemudian muat ulang konfigurasi:

```powershell
php artisan optimize:clear
php artisan optimize
php artisan filament:optimize
```

Dokumen baru akan masuk ke Google Drive. Record lama tetap memakai `storage_disk=local` dan masih dapat diunduh dari lokasi lama, sehingga perubahan ini tidak menghapus atau memutus data yang sudah ada.

## Catatan deployment

- Isi seluruh secret Google Drive melalui environment variables pada hosting, bukan di file yang di-commit.
- Folder Google Drive harus dimiliki atau dibagikan kepada akun OAuth yang menghasilkan refresh token.
- Cadangkan MySQL dan `storage/app/private/documents` sebelum memindahkan file lama.
- Jangan menghapus folder lokal sampai seluruh file lama benar-benar sudah dimigrasikan dan diverifikasi.
