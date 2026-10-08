<div align="center">

# 🦷 Waraswaris

**Sistem Informasi Reservasi, Antrian, dan Rekam Medis Klinik**

Platform web untuk memudahkan pasien membuat reservasi online, resepsionis mengelola antrian, dan dokter mencatat rekam medis dalam satu sistem terpadu.

![Laravel](https://img.shields.io/badge/Laravel-11-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?style=for-the-badge&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-4479A1?style=for-the-badge&logo=mysql&logoColor=white)
![Vite](https://img.shields.io/badge/Vite-5-646CFF?style=for-the-badge&logo=vite&logoColor=white)
![Blade](https://img.shields.io/badge/Blade-Templates-F9322C?style=for-the-badge&logo=laravel&logoColor=white)

</div>

---

## ✨ Fitur Utama

### 👤 Pasien
- Registrasi, login, dan reset password via email
- Pengisian dan pengeditan biodata serta foto profil
- Membuat dan membatalkan reservasi pemeriksaan
- Melihat riwayat reservasi dan riwayat pemeriksaan beserta detailnya

### 🩺 Dokter
- Dashboard ringkasan praktik harian
- Daftar antrian dan detail reservasi pasien
- Pencatatan rekam medis untuk setiap pemeriksaan
- Daftar pasien dan riwayat rekam medis
- Laporan pemeriksaan
- Manajemen profil dan unduh berkas SIP

### 🧾 Resepsionis
- Dashboard operasional klinik
- Pengelolaan daftar antrian harian
- Data pasien beserta detailnya
- Laporan kunjungan

## 🧰 Teknologi

| Layer | Teknologi |
| --- | --- |
| Backend | Laravel 11, PHP 8.2+ |
| Frontend | Blade, CSS, JavaScript |
| Build tool | Vite |
| Database | MySQL |
| Autentikasi | Session-based auth dengan role (pasien, dokter, resepsionis) |

## 🗂️ Struktur Data

Entitas utama pada database:

`akun_user` · `data_pasien` · `data_dokter` · `data_resepsionis` · `jadwal_praktik` · `reservasi` · `antrian` · `rekam_medis`

## 🚀 Instalasi

### Prasyarat
- PHP 8.2 atau lebih baru
- Composer
- Node.js dan npm
- MySQL / MariaDB

### Langkah

```bash
# 1. Clone repository
git clone https://github.com/adityaanandaa1/waraswaris.git
cd waraswaris

# 2. Install dependensi
composer install
npm install

# 3. Siapkan environment
cp .env.example .env
php artisan key:generate
```

Buka `.env`, lalu sesuaikan koneksi database dan konfigurasi email:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=waraswaris
DB_USERNAME=root
DB_PASSWORD=

MAIL_MAILER=smtp
# isi MAIL_HOST, MAIL_USERNAME, MAIL_PASSWORD sesuai penyedia email Anda
```

```bash
# 4. Buat database, lalu jalankan migrasi
php artisan migrate

# 5. Hubungkan storage dan jalankan aplikasi
php artisan storage:link
npm run dev
php artisan serve
```

Aplikasi dapat diakses di `http://127.0.0.1:8000`.

## 📸 Tampilan

<!-- Ganti dengan screenshot aplikasi Anda, simpan di folder docs/ -->
<!-- ![Homepage](docs/homepage.png) -->
<!-- ![Dashboard Pasien](docs/dashboard-pasien.png) -->

## 🔐 Keamanan

Jangan pernah meng-commit file `.env` atau berkas kredensial apa pun. Gunakan `.env.example` sebagai template tanpa nilai rahasia.

## 👨‍💻 Pengembang

Dikembangkan oleh [adityaanandaa1](https://github.com/adityaanandaa1).

## 📄 Lisensi

Proyek ini dibuat untuk keperluan perkuliahan. Dibangun di atas [Laravel](https://laravel.com), yang berlisensi [MIT](https://opensource.org/licenses/MIT).
