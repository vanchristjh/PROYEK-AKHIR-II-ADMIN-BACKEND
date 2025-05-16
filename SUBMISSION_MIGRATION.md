# Solusi Masalah Assignment Submission

Sistem ini sekarang menggunakan dua model untuk pengelolaan pengumpulan tugas:
1. `Submission` (model lama)
2. `AssignmentSubmission` (model baru) 

Permasalahan "Class not found" terjadi karena user model mencoba mereferensikan `AssignmentSubmission` yang belum ada tabelnya di database.

## Perbaikan yang Telah Dilakukan:

1. Migrasi tabel `assignment_submissions` telah berjalan
2. Controller `SubmissionController` telah dimodifikasi untuk:
   - Menggunakan ID parameter daripada model binding
   - Mendukung kedua model secara bersamaan

## Langkah-langkah untuk Migrasi Data:

Untuk memindahkan data dari tabel `submissions` ke tabel `assignment_submissions`, jalankan script berikut:

```
php migrate_submission_data.php
```

Script ini akan memindahkan semua data pengumpulan tugas dari tabel lama ke tabel baru, sambil memastikan tidak ada duplikasi.

## Peringatan:

- Pastikan untuk membuat backup database sebelum menjalankan migrasi
- Jika terjadi error, cek log untuk informasi lebih detail

## Konfigurasi Tambahan:

Jika ingin menggunakan hanya satu model, gunakan opsi berikut:

### Opsi 1: Menggunakan hanya AssignmentSubmission
Modifikasi `User.php` untuk memastikan metode `studentSubmissions()` juga menggunakan model `AssignmentSubmission`

### Opsi 2: Menggunakan hanya Submission
Modifikasi `User.php` untuk memastikan metode `submissions()` menggunakan model `Submission`

## Cara Menjalankan Aplikasi:

```
php artisan serve
```

atau:

```
php -S localhost:8000 -t public
```
