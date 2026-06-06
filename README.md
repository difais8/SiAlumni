# 🎓 SIALUMNI (Sistem Informasi Alumni)

SIALUMNI adalah sebuah aplikasi berbasis web yang dirancang untuk mengelola data alumni, memfasilitasi komunikasi antar lulusan, serta membagikan informasi terkait pengumuman dan acara reuni. Sistem ini dilengkapi dengan manajemen peran pengguna (Role-Based Access Control) untuk memastikan keamanan dan ketepatan akses fitur.

---

## 🚀 Fitur Utama

- **Sistem Autentikasi & Otorisasi:** Registrasi, Login, Lupa Password, dan pembatasan akses ketat berdasarkan peran (Pengelola, Ketua Alumni, Ketua Angkatan, dan Alumni).
- **Manajemen Profil Terpadu:** Pengelolaan profil pengguna yang mencakup informasi dasar, riwayat pekerjaan, riwayat pendidikan, dan galeri foto pribadi.
- **Ruang Diskusi Angkatan:** Forum komunikasi obrolan (chat) internal yang dikelompokkan secara spesifik berdasarkan angkatan.
- **Papan Pengumuman & Acara:** Modul bagi pengelola untuk mempublikasikan berita, acara reuni, atau informasi lowongan pekerjaan dengan dukungan pemformatan teks kaya (Rich Text).
- **Manajemen Data Master:** Modul CRUD komprehensif bagi Pengelola untuk mengatur entitas Angkatan, data Alumni, dan akun Pengelola lainnya.
- **Ekspor Laporan:** Fitur ekstraksi dan pengunduhan rekapitulasi data alumni ke dalam format Spreadsheet (Excel).

---

## 🛠️ Teknologi yang Digunakan

- **Backend:** Laravel 10 (PHP)
- **Database:** MySQL
- **Frontend Framework:** - Bootstrap (Dashboard & UI Utama)
  - Tailwind CSS (Modul Autentikasi bawaan)
- **Authentication:** Laravel Breeze
- **Rich Text Editor:** Summernote
- **Data Export:** Maatwebsite / Laravel Excel

---

## 📋 Persyaratan Sistem

Sebelum menjalankan aplikasi ini, pastikan sistem kamu telah terinstal perangkat lunak berikut:
- PHP ^8.1
- Composer
- Node.js & NPM
- MySQL / MariaDB (XAMPP/Laragon/sejenisnya)

---

## ⚙️ Cara Instalasi & Konfigurasi Lokal

Ikuti langkah-langkah di bawah ini untuk menjalankan proyek SIALUMNI di perangkat lokal:

1. **Clone Repositori**
   ```bash
   git clone https://github.com/difais8/SiAlumni.git
   cd SiAlumni

2. **Instalasi Dependensi PHP**
    ```bash
    composer install
   
3. **Instalasi & Kompilasi Dependensi Fronted**
    ```bash
    npm install
    npm run build
    
4. **Konfigurasi Environment**
    <br>
   Buka file .env dan atur bagian database
   
6. **Generate Application Key**
    ```bash
    php artisan key:generate
   
7. **Migrasi Basis Data**
    ```bash
    php artisan migrate
    php artisan db:seed

Jalankan seed untuk akun awal pengelola
email    : admin@alumni.com
password : 11111111
Atau bisa di cari di file seednya
   
 
7. **Tautkan Storage (Media / Galeri)**
    ```bash
    php artisan serve

8. **Jalankan Aplikasi**
    ```bash
    php artisan serve
