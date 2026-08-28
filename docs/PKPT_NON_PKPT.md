# Modul PKPT dan Non-PKPT

Modul pengelolaan penugasan dibagi menjadi dua dropdown navigasi:

1. **PKPT**
   - Data PKPT
   - Monitoring & Evaluasi
   - Surat Perintah Tugas
   - Kertas Kerja
   - Laporan Hasil Penugasan
2. **Non-PKPT**
   - Data Non-PKPT
   - Monitoring & Evaluasi
   - Surat Perintah Tugas
   - Kertas Kerja
   - Laporan Hasil Penugasan

## Struktur data

- `pkpt_activities`: induk kegiatan PKPT yang sudah ada.
- `non_pkpt_activities`: induk kegiatan Non-PKPT dengan struktur kolom setara `pkpt_activities`.
- `monitoring_evaluations`: dapat berelasi ke `pkpt_activity_id` atau `non_pkpt_activity_id`. Halaman daftar hanya menampilkan status `selesai`.
- `spt_records`: dipisahkan memakai `relation_type`. SPT PKPT wajib memiliki `pkpt_activity_id`, sedangkan SPT Non-PKPT wajib memiliki `non_pkpt_activity_id`.
- `work_papers`: dokumen kertas kerja yang wajib terkait SPT. Format yang diterima: XLSX, XLS, DOCX, atau DOC.
- `assignment_reports`: laporan hasil penugasan yang wajib terkait SPT. Format yang diterima hanya PDF.

Jenis penugasan pada tabel Kertas Kerja dan Laporan Hasil Penugasan dibaca dari `spt_records.assignment_type`, sehingga tidak terjadi duplikasi nilai.

## Menjalankan migration

Sebelum menjalankan migration pada server produksi, buat backup database MySQL:

```bash
mysqldump -u NAMA_USER -p NAMA_DATABASE > backup-sibatig-sebelum-pkpt-non-pkpt.sql
```

Kemudian jalankan:

```bash
php artisan optimize:clear
php artisan migrate --force
php artisan optimize
```

Migration bersifat non-destruktif. Data PKPT, monitoring, dan SPT lama tidak dihapus. SPT lama dengan `relation_type = NON PKPT` otomatis dibuatkan kegiatan induk di `non_pkpt_activities`, kemudian `non_pkpt_activity_id` pada SPT tersebut diisi.

Untuk memeriksa status migration:

```bash
php artisan migrate:status
```

## Penyimpanan file

Kertas kerja dan laporan memakai disk yang sama dengan menu Dokumen:

```env
DOCUMENT_FILESYSTEM_DISK=local
```

atau Google Drive:

```env
DOCUMENT_FILESYSTEM_DISK=google
```

Pengaturan Google Drive selengkapnya tersedia di `docs/GOOGLE_DRIVE_STORAGE.md`.

## Migrasi SQLite ke MySQL

Perintah migrasi lama telah mencakup tabel baru:

```bash
php artisan sibatig:migrate-sqlite-to-mysql \
    --source=/lokasi/database.sqlite \
    --target=mysql
```

Perintah tersebut hanya menerima target MySQL yang kosong dan tidak menghapus database SQLite sumber.
