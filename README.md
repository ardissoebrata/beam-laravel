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
- Node.js 24 dan npm
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

Workflow GitHub Actions di [`.github/workflows/build-deploy.yml`](.github/workflows/build-deploy.yml) memiliki dua job untuk server development:

- `build` berjalan pada pull request, push ke `main`, dan manual dispatch. Job ini menjalankan formatting check, PHPStan, test suite, `npm run build`, lalu membuat artifact dengan dependency Composer development agar Faker tersedia. ESLint sementara tidak dijalankan di GitHub Actions.
- `deploy` hanya berjalan setelah `build` berhasil pada `main` atau manual dispatch. Artifact dikirim ke VPS sebagai release baru dan diaktifkan dengan [`deploy/remote-deploy.sh`](deploy/remote-deploy.sh).
- `deploy` menggunakan mode `development`, sehingga setiap deployment menjalankan `php artisan migrate:fresh --seed --force`. Semua tabel dan data pada database development akan dihapus lalu dibuat ulang.

Workflow ini ditujukan untuk server development dengan `APP_ENV=local` dan `APP_DEBUG=true`. Jangan arahkan workflow ini ke database production karena proses deployment development bersifat destruktif.

### Prasyarat VPS

Siapkan VPS Linux dengan PHP 8.4, PHP-FPM, driver PHP untuk database, queue worker, `tar`, `curl`, dan akses `sudo` terbatas untuk reload PHP-FPM serta restart queue. Nginx harus mengarah ke:

```text
/var/www/beam-laravel/current/public
```

Buat struktur directory berikut dan pastikan user `deploy` dapat menulisnya. Jalankan provisioning berikut sebagai user yang memiliki akses `sudo`:

```bash
sudo install -d -o deploy -g deploy -m 775 /var/www/beam-laravel
sudo install -d -o deploy -g deploy -m 775 /var/www/beam-laravel/releases
sudo install -d -o deploy -g deploy -m 775 /var/www/beam-laravel/shared
sudo install -d -o deploy -g deploy -m 775 /var/www/beam-laravel/shared/storage
sudo install -d -o deploy -g deploy -m 775 /var/www/beam-laravel/shared/database
sudo chown -R deploy:deploy /var/www/beam-laravel
```

Ganti `deploy` jika username pada secret `DEPLOY_USER` berbeda. Perintah ini penting karena GitHub Actions menjalankan `mkdir`, `scp`, ekstraksi artifact, dan pergantian symlink sebagai user tersebut.

Struktur directory yang dihasilkan:

```text
/var/www/beam-laravel/
|-- current -> releases/<release>
|-- releases/
`-- shared/
   |-- .env
   |-- database/
   |  `-- database.sqlite
   `-- storage/
```

Buat `/var/www/beam-laravel/shared/.env` langsung di server. Jangan menyimpan `.env`, `APP_KEY`, password database, atau private key di repository. Queue worker harus menjalankan `php artisan queue:work` melalui Supervisor atau systemd.

Jika server menggunakan SQLite, install driver dan simpan database di luar directory release agar tidak hilang saat release baru dibuat:

```bash
sudo apt install php8.4-sqlite3
sudo install -d -o deploy -g deploy -m 775 /var/www/beam-laravel/shared/database
sudo -u deploy touch /var/www/beam-laravel/shared/database/database.sqlite
```

Atur path absolut di `/var/www/beam-laravel/shared/.env`:

```dotenv
DB_CONNECTION=sqlite
DB_DATABASE=/var/www/beam-laravel/shared/database/database.sqlite
```

SQLite harus dapat ditulis oleh user PHP-FPM dan user `deploy`. SQLite juga dapat membuat file journal, WAL, dan SHM di directory database, sehingga PHP-FPM membutuhkan izin tulis pada file database dan directory induknya. Periksa user pool PHP-FPM terlebih dahulu, lalu ganti `www-data` pada perintah berikut jika berbeda:

```bash
ps aux | grep php-fpm
sudo apt install acl
sudo setfacl -m u:www-data:rwx /var/www/beam-laravel/shared/database
sudo setfacl -d -m u::rwx,g::rwx,o::---,u:www-data:rwx,m::rwx /var/www/beam-laravel/shared/database
sudo setfacl -m u:www-data:rw /var/www/beam-laravel/shared/database/database.sqlite
sudo setfacl -R -m u:www-data:rwx /var/www/beam-laravel/shared/storage
sudo find /var/www/beam-laravel/shared/storage -type d -exec setfacl -d -m u::rwx,g::rwx,o::---,u:www-data:rwx,m::rwx {} +
sudo -u www-data test -r /var/www/beam-laravel/shared/database/database.sqlite
sudo -u www-data test -w /var/www/beam-laravel/shared/database/database.sqlite
sudo -u www-data test -w /var/www/beam-laravel/shared/database
```

Deployment akan membuat `release/database/database.sqlite` sebagai symlink ke file shared tersebut, sementara `database/migrations` tetap berada di setiap release. Jangan gunakan `chmod 777` untuk mengatasi error SQLite readonly.

Directory shared `storage` juga menggunakan ACL karena PHP-FPM membuat file cache dan compiled view di dalamnya. Deployment tidak mengubah permission file storage yang mungkin dibuat oleh PHP-FPM.

Jika menggunakan MySQL/MariaDB, install `php8.4-mysql` dan isi `DB_CONNECTION`, `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, serta `DB_PASSWORD` pada `.env` server.

Script deployment menggunakan service PHP-FPM `php8.4-fpm` secara default. Berikan izin `sudo` tanpa password hanya untuk reload service tersebut:

```bash
echo 'deploy ALL=(root) NOPASSWD: /usr/bin/systemctl reload php8.4-fpm' | sudo tee /etc/sudoers.d/beam-deploy
sudo chmod 440 /etc/sudoers.d/beam-deploy
sudo visudo -cf /etc/sudoers.d/beam-deploy
```

Ganti `deploy` jika username pada secret `DEPLOY_USER` berbeda. Script menggunakan `sudo -n` agar workflow gagal langsung jika izin ini belum tersedia.

### Konfigurasi GitHub

Tambahkan secrets berikut pada **Repository secrets**:

| Secret | Nilai |
| --- | --- |
| `DEPLOY_HOST` | Hostname atau IP VPS |
| `DEPLOY_DOMAIN` | Domain aplikasi yang digunakan Nginx, misalnya `beam-laravel.mybeam.me` |
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

Push perubahan ke `main` atau jalankan workflow manual pada branch `main` dari tab **Actions**. Job `deploy` akan membuat release dengan format UTC `YYYYMMDDHHMMSS-run_number`, mengekstrak artifact, menjalankan `migrate:fresh --seed`, menjalankan cache, mengganti symlink `current`, me-restart queue worker, dan me-reload PHP-FPM.

Setelah health check berhasil, artifact GitHub Actions akan dihapus otomatis. Jika deployment gagal, artifact tetap tersedia untuk debugging.

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
