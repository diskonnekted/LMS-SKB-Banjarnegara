# LMS SKB Banjarnegara

Aplikasi Learning Management System (LMS) berbasis Laravel untuk kebutuhan SKB Banjarnegara.

Repo: https://github.com/diskonnekted/LMS-SKB-Banjarnegara

## Fitur Utama

- Manajemen pengguna berbasis peran: Admin, Guru, Siswa
- Pelajaran (Course): judul, kategori, tingkat kelas (grade level), thumbnail, publish/unpublish
- Struktur materi: Modul → Pelajaran (teks/video/pdf/ppt) → Kuis → Soal
- Enroll siswa ke pelajaran dan pelacakan progres belajar
- Sertifikat (download + verifikasi kode)
- Berita (News) dan pengaitan berita ke pelajaran
- Katalog pelajaran publik + filter kategori dan kelas

## Teknologi

- Laravel 12 + Breeze (auth)
- Tailwind CSS + Vite
- Spatie Laravel Permission (role)
- PHPUnit (testing), Laravel Pint (formatting)

## Struktur Singkat

- `routes/web.php`: routing utama (publik, dashboard, admin/teacher/student)
- `app/Http/Controllers`: controller untuk Course, Learning, Quiz, News, dll.
- `resources/views`: Blade UI (admin/teacher/student/public)
- `database/migrations`: struktur tabel LMS (courses/modules/lessons/quizzes, dsb.)
- `database/seeders`: data awal user/role dan dummy course

## Grade Level (Kejar Paket)

Nilai `grade_level` pada course menggunakan format:

- Kejar Paket A: Kelas 3–6
- Kejar Paket B: Kelas 7–9
- Kejar Paket C: Kelas 10–12

Input lama seperti `Kelas 3 SD`, `Kelas 7 SMP`, `Kelas 10 SMA` dinormalisasi otomatis menjadi format Kejar Paket.

## Kebutuhan Sistem

- PHP 8.2+
- Composer
- Node.js + npm
- Database:
  - Default `.env.example` memakai SQLite (`DB_CONNECTION=sqlite`)
  - Bisa diganti ke MySQL/MariaDB sesuai kebutuhan

## Instalasi Cepat

### 1) Install dependency

```bash
composer install
npm install
```

### 2) Konfigurasi environment

```bash
copy .env.example .env
php artisan key:generate
```

Jika memakai SQLite, buat file `database/database.sqlite` lalu pastikan `.env`:

```env
DB_CONNECTION=sqlite
```

Jika memakai MySQL/MariaDB, set `DB_CONNECTION`, `DB_HOST`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`.

### 3) Migrasi & seeding

```bash
php artisan migrate --seed
```

Seeder utama: `DatabaseSeeder` (membuat roles + akun default + dummy course).

## Menjalankan Aplikasi

### Opsi A (minimal)

```bash
php artisan serve
npm run dev
```

### Opsi B (mode dev terintegrasi)

```bash
composer run dev
```

Script ini menjalankan server, queue listener, log viewer (pail), dan vite bersamaan.

## Akun Default

Dari `DatabaseSeeder`:

- Admin: `admin@skb.com` / `password`
- Guru: `guru@skb.com` / `password`
- Siswa: `student@skb.com` / `password`

Login URL:

- `http://localhost/skb/public/login` (jika dipasang di XAMPP/htdocs)

## URL Penting

- Beranda: `/`
- Katalog publik: `/courses/all`
- Dashboard: `/dashboard` (butuh login)

## Testing & Quality

Jalankan test:

```bash
composer test
```

Jalankan formatter:

```bash
php vendor/bin/pint
```

Build aset:

```bash
npm run build
```

## Lisensi

MIT
