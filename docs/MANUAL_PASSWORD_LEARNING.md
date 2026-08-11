# Manual Fitur Password Mata Pelajaran

## Ringkasan Perubahan

Fitur password memungkinkan guru melindungi akses ke mata pelajaran (course). Siswa perlu memasukkan password yang diberikan guru sebelum bisa mengakses pelajaran-pelajaran di dalam mata pelajaran tersebut.

**Perubahan utama:**
- Password disimpan di level **Mata Pelajaran (Course)**, bukan per pelajaran
- Satu password untuk semua pelajaran dalam satu mata pelajaran
- Setelah siswa berhasil memasukkan password, session tersimpan → tidak perlu input ulang selama browsing

---

## Cara untuk Guru

### 1. Membuat Mata Pelajaran Baru dengan Password

1. Login sebagai guru/admin
2. Klik menu **"Mata Pelajaran"** → **"Buat Mata Pelajaran Baru"**
3. Isi form:
   - **Judul Mata Pelajaran**: Contoh "Matematika"
   - **Kategori Mata Pelajaran**: Pilih kategori
   - **Kelas**: Pilih kelas
   - **Password (Opsional)**: Masukkan password yang ingin diberikan ke siswa
   - Isi field lainnya (deskripsi, CP, tujuan pembelajaran, dll)
4. Klik **"Buat"**

**Contoh:**
- Mata Pelajaran: "Matematika"
- Kelas: "Kesetaraan Paket C Kelas 10"
- Password: "mat123"

Siswa yang ingin mengakses Matematika harus memasukkan password `mat123`.

### 2. Mengedit Password Mata Pelajaran Existing

1. Buka menu **"Mata Pelajaran"**
2. Klik **"Edit"** pada mata pelajaran yang ingin diubah password-nya
3. Di bagian **"Password (Opsional)"**:
   - **Jika sudah ada password**: Kosongkan untuk menghapus password, atau isi password baru
   - **Jika belum ada password**: Isi password baru
4. Klik **"Perbarui"**

### 3. Menambahkan/Pengaturan Pelajaran dalam Mata Pelajaran

Setelah mata pelajaran dibuat dengan password, guru bisa:
- Tambah modul dan pelajaran seperti biasa
- Semua pelajaran dalam mata pelajaran tersebut akan dilindungi oleh satu password yang sama
- Tidak perlu setting password per pelajaran

---

## Cara untuk Siswa

### 1. Mendaftar ke Mata Pelajaran

1. Login ke aplikasi
2. Cari dan pilih mata pelajaran yang ingin diikuti
3. Klik **"Daftar"** atau **"Enroll"**

### 2. Mengakses Pelajaran Pertama

1. Setelah enroll, klik mata pelajaran tersebut
2. Akan muncul **modal overlay** dengan judul **"Mata Pelajaran Dilindungi Password"**
3. Masukkan password yang diberikan guru
4. Klik **"Buka Mata Pelajaran"**

**Contoh tampilan modal:**
```
┌─────────────────────────────────┐
│        🔒 (icon gembok)         │
│  Mata Pelajaran Dilindungi      │
│        Password                 │
│                                 │
│  Masukkan password yang         │
│  diberikan guru untuk         │
│  mengakses mata pelajaran ini.  │
│                                 │
│  [  masukkan password  ]        │
│                                 │
│  [  🔓 Buka Mata Pelajaran  ]   │
└─────────────────────────────────┘
```

### 3. Jika Password Salah

- Akan muncul pesan error: **"Password salah"**
- Coba lagi dengan password yang benar
- Hubungi guru jika tidak yakin password-nya

### 4. Akses Pelajaran Berikutnya

Setelah berhasil memasukkan password untuk pelajaran pertama:
- Session tersimpan otomatis
- Guru bisa berpindah ke pelajaran berikutnya tanpa input ulang password
- Password hanya perlu diinput sekali per sesi browsing

### 5. Ketika Password Diperlukan Lagi

Siswa perlu input ulang password jika:
- Menutup browser sepenuhnya
- Session habis (timeout)
- Membersihkan cache/cookies browser

---

## Troubleshooting

### Guru

| Masalah | Solusi |
|---|---|
| Siswa bilang tidak bisa masuk | Pastikan password sudah disimpan saat edit course |
| Ingin ganti password | Edit course → ubah password → beri tahu siswa |
| Ingin hapus password | Edit course → kosongkan field password → simpan |

### Siswa

| Masalah | Solusi |
|---|---|
| Password salah | Periksa kembali dengan guru, pastikan tidak ada typo |
| Harus input terus password | Coba buka ulang browser, pastikan tidak incognito |
| Modal password tidak muncul | Refresh halaman, pastikan sudah enroll course |

---

## Contoh Penggunaan

### Skenario 1: Mata Pelajaran Umum (Tanpa Password)

Guru membuat mata pelajaran "PKN" tanpa password:
- Siswa enroll → langsung bisa akses semua pelajaran
- Cocok untuk materi yang terbuka untuk semua siswa

### Skenario 2: Mata Pelajaran Ujian (Dengan Password)

Guru membuat mata pelajaran "UTS Matematika" dengan password:
- Password: `uts2024`
- Siswa baru bisa akses saat guru memberi tahu password
- Cocok untuk materi yang hanya boleh diakses pada waktu tertentu

### Skenario 3: Password Berbeda per Kelas

Guru mengajar beberapa kelas:
- Kelas 10: Password `mat10`
- Kelas 11: Password `mat11`
- Kelas 12: Password `mat12`
- Setiap kelas punya mata pelajaran terpisah dengan password berbeda

---

## Catatan Teknis

- Password disimpan dalam bentuk plain text di database
- Password divalidasi via session (tidak perlu re-login)
- Password maksimal 50 karakter
- Field password bersifat opsional (bisa dikosongkan)
- Perubahan password bersifat instant → siswa yang sudah login perlu refresh untuk melihat perubahan
