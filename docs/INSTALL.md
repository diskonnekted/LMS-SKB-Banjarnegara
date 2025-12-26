# Instruksi Install & Deploy (Shared Hosting tanpa root)

## Prasyarat
- PHP `8.2+` dengan ekstensi: `mbstring`, `openssl`, `pdo_mysql`, `curl`, `fileinfo`, `json`
- MySQL/MariaDB aktif dan kredensial database
- Composer 2.x (opsional; bisa upload `vendor/` dari lokal)
- Node.js hanya diperlukan di lokal untuk build aset (`vite`)
- HTTPS pada domain untuk fitur PWA

## Struktur Direktori
- Aplikasi: `~/apps/skb`
- Web root: biasanya `~/public_html` (atau setara pada hosting Anda)
- Aset publik Laravel: folder `public` di dalam aplikasi

## Langkah Deploy

### 1) Persiapan Direktori
```bash
mkdir -p ~/apps/skb
cd ~/apps/skb
```

### 2) Upload Source Code
- Upload seluruh isi proyek ke `~/apps/skb` kecuali `node_modules`
- Opsi A (disarankan, jika Composer tersedia di server):
```bash
composer install --no-dev --optimize-autoloader
```
- Opsi B (jika Composer tidak tersedia):
  - Jalankan `composer install --no-dev` di lokal
  - Upload folder `vendor/` ke server

### 3) Build Aset Frontend di Lokal
```bash
npm ci
npm run build
```
- Upload folder hasil build: `public/build/` ke server pada lokasi yang sama

### 4) Tautkan Web Root ke `public`
- Jika bisa symlink:
```bash
mv ~/public_html ~/public_html_bak
ln -s ~/apps/skb/public ~/public_html
```
- Jika symlink tidak diizinkan:
  - Copy isi `public/*` ke `~/public_html/`
  - Pastikan `index.php` di `~/public_html` menggunakan path relatif ke root aplikasi:
    - `require __DIR__.'/../vendor/autoload.php';`
    - `$app = require_once __DIR__.'/../bootstrap/app.php';`

### 5) Hak Akses
```bash
chmod -R u+rwX storage bootstrap/cache
```

### 6) Konfigurasi Lingkungan
```bash
cp .env.example .env
```
Edit `.env`:
```
APP_ENV=production
APP_DEBUG=false
APP_URL=https://domain-anda.com

DB_CONNECTION=mysql
DB_HOST=host-db
DB_PORT=3306
DB_DATABASE=nama_db
DB_USERNAME=user_db
DB_PASSWORD=pass_db

FILESYSTEM_DISK=public
QUEUE_CONNECTION=database
```

### 7) Inisialisasi Aplikasi
```bash
php artisan key:generate --force
php artisan storage:link
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan migrate --force
```

## Scheduler & Queue (tanpa root)

### Cron Laravel Scheduler
```bash
crontab -e
```
Tambahkan:
```
* * * * * /usr/bin/php ~/apps/skb/artisan schedule:run >> ~/logs/laravel-schedule.log 2>&1
```

### Queue Worker via Cron
Gunakan driver `database` di `.env`, lalu:
```
* * * * * /usr/bin/php ~/apps/skb/artisan queue:work --stop-when-empty >> ~/logs/laravel-queue.log 2>&1
```

## PWA
- Pastikan domain menggunakan HTTPS
- File penting:
  - `public/manifest.webmanifest`
  - `public/sw.js`
  - Ikon dinamis tersedia: `/icons/icon-192.png`, `/icons/icon-512.png`

## Troubleshooting
- 500 error: sementara set `APP_DEBUG=true`, cek `storage/logs/laravel.log`
- 404 route: pastikan web root diarahkan ke folder `public` Laravel dan `.htaccess` aktif
- CSS/JS tidak muncul: pastikan `public/build/assets/app-*.css/js` termuat; jalankan `npm run build` di lokal dan upload ulang

## Catatan
- Framework: Laravel `12.x` (lihat `composer.json`)
- PHP minimal: `^8.2`
- Auth & tooling: Laravel Breeze, Vite, Tailwind
