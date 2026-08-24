# Checklist verifikasi OAuth Google untuk SIBATIG

## Halaman yang telah disiapkan

Ganti `https://domain-anda.go.id` dengan domain production yang benar:

- Homepage: `https://domain-anda.go.id/`
- Privacy Policy: `https://domain-anda.go.id/privacy-policy`
- Terms of Service: `https://domain-anda.go.id/terms-of-service`
- OAuth callback: `https://domain-anda.go.id/admin/google-drive/oauth/callback`

Semua halaman publik harus mengembalikan HTTP 200 tanpa login, tidak boleh mengarah ke localhost, dan harus memakai HTTPS.

## 1. Deployment dan domain

1. Deploy Laravel ke hosting yang mendukung PHP, MySQL, persistent storage, dan HTTPS.
2. Arahkan domain atau subdomain milik organisasi ke hosting tersebut.
3. Atur environment production:

   ```dotenv
   APP_ENV=production
   APP_DEBUG=false
   APP_URL=https://domain-anda.go.id
   GOOGLE_DRIVE_REDIRECT_URI=https://domain-anda.go.id/admin/google-drive/oauth/callback
   PUBLIC_ORGANIZATION_NAME="Inspektorat Kota Kediri"
   PUBLIC_CONTACT_EMAIL=email-dukungan-yang-aktif@domain-anda.go.id
   ```

4. Jalankan `php artisan optimize:clear`, migration, dan cache production.
5. Pastikan homepage, Privacy Policy, dan Terms dapat dibuka dari jaringan publik tanpa autentikasi.

GitHub Pages tidak dapat menjalankan backend Laravel/PHP atau MySQL. GitHub tetap dapat digunakan sebagai repository dan sumber deployment, tetapi aplikasi harus dijalankan pada hosting Laravel.

## 2. Verifikasi kepemilikan domain

1. Masuk ke Google Search Console memakai akun Google yang menjadi Owner atau Editor project Google Cloud.
2. Tambahkan **Domain property**, bukan hanya URL-prefix.
3. Masukkan root domain, misalnya `kedirikota.go.id`.
4. Tambahkan TXT record yang diberikan Google ke DNS domain.
5. Klik Verify dan pastikan akun tersebut tercatat sebagai verified owner.

Jika domain organisasi dikelola pihak lain, minta administrator DNS menambahkan TXT record. Jangan memakai domain pihak ketiga yang kepemilikannya tidak dapat diverifikasi.

## 3. Google Auth Platform — Branding

Isi data berikut secara konsisten:

- App name: `SIBATIG`
- User support email: alamat email aktif pada domain organisasi
- App logo: logo Irban 3 yang digunakan pada website
- App home page: `https://domain-anda.go.id/`
- Privacy policy: `https://domain-anda.go.id/privacy-policy`
- Terms of service: `https://domain-anda.go.id/terms-of-service`
- Authorized domain: root domain yang sudah diverifikasi
- Developer contact: alamat email aktif yang dipantau

URL homepage tidak boleh mengalihkan pengguna ke login atau ke domain berbeda.

## 4. OAuth client production

Pada OAuth Client bertipe **Web application**:

- Tambahkan production callback ke Authorized redirect URIs.
- Hapus redirect URI yang tidak digunakan.
- Pertahankan callback localhost hanya pada client development terpisah.
- Gunakan project/client terpisah untuk development dan production bila memungkinkan.

## 5. Data Access

SIBATIG meminta scope:

```text
https://www.googleapis.com/auth/drive.file
```

Justifikasi yang dapat digunakan:

> SIBATIG uses the Google Drive `drive.file` scope to create and manage files uploaded by authorized users within the application-managed SIBATIG folder. The application does not read the user's entire Google Drive. Files are used only for official SPT, work-program, and report storage, and are never sold or used for advertising.

Pastikan scope pada Google Cloud sama dengan scope yang digunakan aplikasi. Jangan menambahkan scope Drive penuh jika tidak diperlukan.

## 6. Sebelum mengirim ulang

- Uji ketiga URL publik menggunakan browser incognito tanpa login.
- Pastikan sertifikat HTTPS valid dan tidak ada redirect ke localhost.
- Pastikan email support aktif.
- Pastikan Privacy Policy di homepage sama persis dengan URL pada OAuth Branding.
- Pastikan domain telah diverifikasi oleh akun Owner/Editor project.
- Rekam demo OAuth jika Google meminta scope justification/video.
- Rotasi Client Secret yang pernah tertempel pada file Markdown.
- Batalkan verification lama jika Google mengunci perubahan branding, perbarui data, lalu kirim ulang.
