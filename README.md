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
- Laravel Boost untuk membantu pengembangan berbasis konteks Laravel
- PrimeVue untuk komponen UI, termasuk DataTable untuk menampilkan data tabular
- Laravel Precognition untuk validasi form secara real-time
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

> Tunggu hingga muncul pesan '**Inertia SSR module graph warmed up**' sebelum membuka aplikasi di browser.

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

## Setup Workflow Build, Test, dan Deploy

Workflow GitHub Actions di [`.github/workflows/build-deploy.yml`](.github/workflows/build-deploy.yml) memiliki dua job:

- `build` berjalan pada pull request, push ke `main`, dan manual dispatch. Job ini menjalankan `composer ci:check`, `npm run build`, lalu membuat artifact production.
- `deploy` hanya berjalan setelah `build` berhasil pada `main` atau manual dispatch. Artifact dikirim ke VPS sebagai release baru dan diaktifkan dengan [`deploy/remote-deploy.sh`](deploy/remote-deploy.sh).

### Prasyarat VPS

Siapkan VPS Linux dengan PHP 8.4, PHP-FPM, database production, queue worker, `tar`, `curl`, dan akses `sudo` terbatas untuk reload PHP-FPM serta restart queue. Nginx harus mengarah ke:

```text
/var/www/beam-laravel/current/public
```

Buat struktur directory berikut dan pastikan user deploy dapat menulisnya:

```text
/var/www/beam-laravel/
|-- current -> releases/<release>
|-- releases/
`-- shared/
   |-- .env
   `-- storage/
```

Buat `/var/www/beam-laravel/shared/.env` langsung di server. Jangan menyimpan `.env`, `APP_KEY`, password database, atau private key di repository. Queue worker harus menjalankan `php artisan queue:work` melalui Supervisor atau systemd.

Script deployment menggunakan service PHP-FPM `php8.4-fpm` secara default. User deploy perlu memiliki izin untuk menjalankan `sudo systemctl reload php8.4-fpm` dan `php artisan queue:restart`.

### Konfigurasi GitHub

Buat GitHub Environment bernama `production`, lalu tambahkan secrets berikut pada environment tersebut:

| Secret | Nilai |
| --- | --- |
| `DEPLOY_HOST` | Hostname atau IP VPS |
| `DEPLOY_USER` | Username Linux untuk deploy |
| `DEPLOY_SSH_KEY` | Private key SSH untuk user deploy |
| `DEPLOY_KNOWN_HOSTS` | Host key VPS yang telah diverifikasi |
| `DEPLOY_PORT` | Opsional, default `22` |

Tambahkan public key pasangan `DEPLOY_SSH_KEY` ke `~/.ssh/authorized_keys` user deploy. Isi `DEPLOY_KNOWN_HOSTS` dari host key yang diperoleh melalui jaringan tepercaya, misalnya:

```bash
ssh-keyscan -p 22 example.com
```

Aktifkan required reviewers pada environment `production` bila deploy perlu persetujuan manual. Workflow tidak menjalankan deploy dari pull request.

### Deploy dan Rollback

Push perubahan ke `main` atau jalankan workflow manual pada branch `main` dari tab **Actions**. Job `deploy` akan membuat release berdasarkan commit SHA, mengekstrak artifact, menjalankan migration/cache, mengganti symlink `current`, me-restart queue worker, dan me-reload PHP-FPM.

Workflow memeriksa endpoint health Laravel `/up` setelah deployment. Periksa juga log Laravel dan status queue worker di VPS.

Lima release terakhir disimpan di `releases/`. Untuk rollback, arahkan symlink `current` ke release sebelumnya, lalu reload PHP-FPM dan restart queue worker:

```bash
cd /var/www/beam-laravel
ln -sfn releases/<release-sebelumnya> current
sudo systemctl reload php8.4-fpm
php current/artisan queue:restart
```

Rollback kode tidak otomatis membatalkan migration database. Pastikan migration production kompatibel dengan release sebelumnya sebelum melakukan rollback.

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
