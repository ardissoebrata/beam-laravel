# Beam Laravel

Starter kit Laravel 13 yang berfungsi sebagai fondasi dasar untuk membangun berbagai aplikasi web secara lebih cepat dan konsisten.

Proyek ini menyediakan struktur awal backend, autentikasi, frontend, otorisasi, testing, dan tooling pengembangan. Fitur bisnis dapat ditambahkan sesuai kebutuhan aplikasi yang akan dibangun.

## Stack Utama

- Laravel 13
- PHP 8.4 atau lebih baru
- Vue 3 dan Inertia.js 3
- Tailwind CSS 4
- Laravel Fortify untuk autentikasi
- Laravel Wayfinder untuk route helper TypeScript
- Spatie Laravel Permission untuk role dan permission
- Pest 5 untuk testing

## Prasyarat

Pastikan perangkat pengembangan sudah memiliki:

- PHP 8.4+
- Composer
- Node.js dan npm
- Database yang didukung Laravel

Konfigurasi bawaan menggunakan SQLite.

## Instalasi

1. Clone repository dan masuk ke folder proyek.

   ```bash
   git clone <url-repository> beam-laravel
   cd beam-laravel
   ```

2. Jalankan setup otomatis.

   ```bash
   composer run setup
   ```

Perintah tersebut akan menginstal dependensi, membuat file `.env`, menghasilkan application key, menjalankan migrasi, menginstal dependensi frontend, dan membuat build frontend.

## Menjalankan Aplikasi

Untuk menjalankan server Laravel, queue worker, dan Vite secara bersamaan:

```bash
php artisan dev
```

```text
Note: tunggu hingga muncul pesan 'Inertia SSR module graph warmed up' sebelum membuka aplikasi di browser.
```

Aplikasi tersedia di `http://localhost:8000`.

Untuk menjalankan Vite saja:

```bash
npm run dev
```

## Testing dan Quality Check

Menjalankan seluruh test:

```bash
php artisan test --compact
```

Menjalankan pemeriksaan lengkap proyek:

```bash
composer run ci:check
```

Perintah quality check mencakup formatter PHP, lint dan type check frontend, analisis PHPStan, serta test suite.

## Struktur Penting

```text
app/                 Kode aplikasi Laravel
database/            Migration, factory, dan seeder
resources/js/        Halaman dan komponen Vue
resources/css/       Style aplikasi
routes/              Definisi route aplikasi
tests/               Feature test dan unit test
```

## Memulai Pengembangan

Setelah setup selesai, gunakan proyek ini sebagai titik awal untuk:

1. Menambahkan model, migration, dan factory untuk domain aplikasi.
2. Menambahkan route dan controller di sisi backend.
3. Membuat halaman Vue di `resources/js/pages`.
4. Menentukan role dan permission sesuai kebutuhan akses.
5. Menambahkan feature test untuk setiap alur penting.

## Lisensi

Proyek ini menggunakan lisensi MIT.
