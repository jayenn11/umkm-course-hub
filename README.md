# UMKM Course Hub 🚀

> **Platform E-Learning & Literasi Kewirausahaan Digital untuk Pelaku Usaha Mikro, Kecil, dan Menengah (UMKM)**

Aplikasi berbasis web yang dibangun menggunakan **Laravel 11**, **Tailwind CSS**, dan **MySQL** untuk membantu pelaku UMKM mempelajari strategi pemasaran, pencatatan kas, dan sertifikasi produk secara mandiri dan terstruktur.

---

## 🎨 Design System
Platform ini mengadopsi palet warna resmi **"Arctic Reflection"** untuk menghadirkan antarmuka yang bersih, tenang, dan profesional:
- **Primary (`#243C4C`)**: Header thumbnail, tombol utama, filter aktif, dan navigasi.
- **Secondary (`#5289AD`)**: Teks link interaktif, fokus input, dan tombol aksi sekunder.
- **Surface (`#F4FCFB`)**: Latar belakang aplikasi.

---

## ✨ Fitur Utama

### 👤 Role Peserta (Pelaku UMKM)
- **Registrasi & Autentikasi**: Registrasi khusus pelaku usaha dengan menyertakan nama usaha.
- **Katalog Kursus Interaktif**: Pencarian instan (*live search* debounce 300ms), filter kategori, dan badge penanda status terdaftar.
- **Pendaftaran Kelas Instan (*Free Enrollment*)**: Satu klik tanpa biaya, otomatis langsung diarahkan ke materi pertama.
- **Ruang Belajar (*Course Player*)**: Video player responsif (YouTube embed), rangkuman bacaan, dan tombol navigasi materi.
- **Pelacak Kemajuan Mandiri**: Tombol toggle "Tandai Selesai", bar persentase penyelesaian kursus, dan modal kelulusan formal.
- **Kelas Saya**: Daftar kursus aktif dengan pagination teratur (6 item per halaman).

### 🛠️ Role Administrator
- **Dasbor Metrik Operasional**: Statistik total kursus, total materi, total peserta UMKM, total pendaftaran, dan riwayat aktivitas pendaftaran terkini.
- **Manajemen Kategori & Kursus (Modal UI)**: Tambah dan ubah data langsung melalui modal dialog tanpa me-reload halaman.
- **Manajemen Materi (*Drag & Drop Reordering*)**: Pengurutan silabus materi menggunakan SortableJS tanpa hambatan lintas halaman.
- **Integritas Relasi Basis Data**: Proteksi pencegahan hapus kursus/kategori yang masih memiliki data turunan aktif.
- **Pencegahan Stale Data**: Proteksi `PreventBackHistory Middleware` dan penyeragaman zona waktu `Asia/Jakarta` (WIB).

---

## 💻 Tech Stack
- **Backend Framework**: Laravel 11 (PHP 8.2+)
- **Frontend / Styling**: Blade Engine + Tailwind CSS v3
- **Interaktivitas**: Alpine.js & SortableJS
- **Database**: MySQL 8.0
- **Build Tool**: Vite

---

## 🚀 Panduan Instalasi Lokal

### 1. Kloning Repositori
git clone https://github.com/<username-anda>/umkm-course-hub.git
cd umkm-course-hub

### 2. Pasang Dependensi
composer install
npm install

### 3. Konfigurasi Lingkungan (.env)
cp .env.example .env
php artisan key:generate

Sesuaikan konfigurasi basis data pada `.env`:
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=umkm_course_hub
DB_USERNAME=root
DB_PASSWORD=

### 4. Migrasi & Seeding Data
php artisan migrate --seed

### 5. Kompilasi Aset & Jalankan Server
# Terminal 1: Kompilasi aset frontend
npm run dev

# Terminal 2: Jalankan web server lokal
php artisan serve

Akses aplikasi melalui peramban: http://127.0.0.1:8000

---

## 🔑 Kredensial Akun Uji Coba

| Peran (Role)      | Email                        | Password   | Keterangan                       |
| :---------------- | :--------------------------- | :--------- | :------------------------------- |
| **Administrator** | `admin@umkm.id`              | `password` | Akses penuh modul `/admin`       |
| **Peserta UMKM**  | `fajartrysutrasno@gmail.com` | `password` | Akun peserta dengan profil usaha |
