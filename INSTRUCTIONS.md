# Panduan Menjalankan Seeder

Untuk menambahkan akun administrator ke database, ikuti langkah-langkah berikut:

## 1. Pastikan Database Sudah Dikonfigurasi

Pastikan file `.env` sudah berisi konfigurasi database yang benar, contoh:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=admin_dashboard
DB_USERNAME=root
DB_PASSWORD=
```

## 2. Jalankan Migrasi dan Seeder

Buka terminal (Command Prompt atau PowerShell), lalu arahkan ke direktori project dan jalankan perintah berikut:

```bash
cd "D:\Folder Kuliah\Project\Project Kuliah\PA 2\XXX\admin-dashboard"
php artisan migrate:fresh --seed
```

## 3. Detail Akun Admin

Setelah seeder berhasil dijalankan, Anda dapat menggunakan akun admin berikut:

-   **Email**: admin@sma.sch.id
-   **Password**: password

## 4. Menjalankan Aplikasi

Untuk menjalankan aplikasi, gunakan perintah:

```bash
php artisan serve
```

Kemudian buka browser dan akses `http://localhost:8000`

## Troubleshooting

### Jika Terjadi Error

1. Pastikan database MySQL sudah berjalan
2. Pastikan nama database sudah dibuat
3. Periksa kredensial database di file `.env`
4. Jalankan perintah `php artisan config:clear` untuk membersihkan cache konfigurasi

### Cara Membuat Akun Admin Secara Manual

Jika seeder tidak berfungsi, Anda bisa menambahkan akun admin secara manual melalui `php artisan tinker`:

```bash
php artisan tinker
```

Kemudian masukkan kode berikut:

```php
use App\Models\User;
use Illuminate\Support\Facades\Hash;

User::create([
    'name' => 'Administrator',
    'email' => 'admin@sma.sch.id',
    'password' => Hash::make('password'),
    'email_verified_at' => now(),
]);
```

Tekan Enter untuk menjalankan kode tersebut.
