# 🎪 Sistem Antrian Pameran (Exhibition Queue System)

Sistem pendaftaran antrian pameran stand Foto (FT) dan Lukis (LK) berbasis web yang efisien, responsif, dan terintegrasi dengan REST API. Aplikasi ini dirancang untuk mengelola kuota harian pengunjung dengan sistem penomoran otomatis yang unik.

---

## ✨ Fitur Utama

- **Real-time Quota Monitoring**: Cek sisa kuota stand berdasarkan tanggal secara otomatis melalui AJAX.
- **RESTful API Architecture**: Komunikasi *frontend* dan *backend* yang bersih menggunakan standar REST (GET, POST).
- **Single Page Experience**: Seluruh aksi (Create, Read) dilakukan tanpa *full page reload* untuk pengalaman pengguna yang mulus.
- **Dynamic DataTables**: Daftar antrian yang dapat difilter berdasarkan tanggal dan jenis stand secara instan.
- **Ticket Export**: Kemampuan untuk mengunduh bukti nomor antrian dalam format **JPG** (Image) dan **PDF**.
- **Security & Validation**: Pencegahan pemesanan ganda (satu email per stand per hari) dan validasi kapasitas maksimal stand.
- **Daily Reset Counter**: Sistem penomoran antrian pintar yang di-reset setiap hari dengan format `{KODE}{YYYY}{MM}{DD}{COUNTER}`.

---

## 💻 Persyaratan Sistem

Pastikan perangkat Anda memenuhi spesifikasi berikut sebelum menjalankan aplikasi:

- **PHP**: ^8.1 atau lebih tinggi
- **Composer**: Dependency Manager untuk PHP
- **Database**: MySQL / MariaDB
- **Web Browser**: Chrome, Edge, Firefox, atau Safari versi terbaru
- **Web Server**: Apache / Nginx (Atau menggunakan `php artisan serve`)

---

## 🚀 Cara Instalasi

Ikuti langkah-langkah di bawah ini untuk memasang project di lingkungan lokal Anda:

### 1. Persiapan Project
Clone repositori atau masuk ke direktori project, lalu instal dependensi melalui Composer:
```bash
composer install
```

### 2. Konfigurasi Database
Salin file `.env.example` menjadi `.env` dan sesuaikan konfigurasi database Anda. Pastikan database `tabel-ski` sudah dibuat di MySQL Anda.
```bash
cp .env.example .env
# Sesuaikan DB_DATABASE, DB_USERNAME, dan DB_PASSWORD di dalam file .env
```

### 3. Generate Application Key
```bash
php artisan key:generate
```

### 4. Migrasi & Seeding
Jalankan migrasi untuk membuat tabel dan masukkan data awal (quota stand & data dummy pengetesan):
```bash
# Menjalankan migrasi dan seeder utama
php artisan migrate --seed

# (Opsional) Menjalankan data dummy tambahan untuk simulasi kuota penuh
php artisan db:seed --class=DummyBookingSeeder
```

### 5. Jalankan Aplikasi
Jalankan server pengembangan lokal Laravel:
```bash
php artisan serve
```
Akses aplikasi melalui browser di alamat: `http://127.0.0.1:8000`

---

## 🛠️ Tech Stack

- **Backend**: Laravel 11.x (PHP 8.2)
- **Frontend**: HTML5, Bootstrap 3, jQuery, CSS3
- **Libraries**:
    - **DataTables**: Untuk tabel dinamis & filter.
    - **SweetAlert2**: Untuk notifikasi visual yang modern.
    - **html2canvas & jsPDF**: Untuk proses ekspor tiket antrian.
    - **jQuery UI**: Untuk pemilihan tanggal (Datepicker).

---
*Dikembangkan untuk keperluan Technical Test - 2026*
