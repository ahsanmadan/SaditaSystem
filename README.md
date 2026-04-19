<div align="center">

# 🌸 SaditaSystem

### Sistem Informasi E-Commerce & Manajemen Dekorasi Berbasis Web

[![Laravel](https://img.shields.io/badge/Laravel-12.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com)
[![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-4.0-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white)](https://tailwindcss.com/)
[![SQLite](https://img.shields.io/badge/SQLite-07405E?style=for-the-badge&logo=sqlite&logoColor=white)](https://sqlite.org/)

</div>

---

## 📖 Tentang Aplikasi

**SaditaSystem** adalah platform *e-commerce* dan sistem informasi manajemen penyewaan dekorasi, hantaran, serta florist khusus untuk UMKM **Sadita Decoration** di Padang. 

Aplikasi ini bertujuan untuk mendigitalisasi proses pemesanan yang sebelumnya manual via WhatsApp. Fitur utama yang dikembangkan antara lain:
- Katalog produk digital interaktif.
- Sistem *booking* online dan pengecekan ketersediaan jadwal.
- Manajemen inventaris stok barang dekorasi/seserahan.
- Integrasi *Chatbot AI* otomatis untuk menjawab pertanyaan pelanggan.

---

## 👥 Tim Pengembang (Kelompok 5)

Proyek ini dirancang dan dikembangkan bersama-sama oleh **Kelompok 5** (Semester 4), dengan rincian anggota tim dan perannya masing-masing:

| Nama Lengkap | NIM | Peran / Role | Akun GitHub |
|---|---|---|---|
| **Bagastio Putra Joandri** | 2411081005 | Project Manager & AI Specialist | [@git-bjoand](https://github.com/git-bjoand) |
| **Ahsan Ramadan** | 2411081002 | Lead Programmer | [@ahsanmadan](https://github.com/ahsanmadan) |
| **Jeli Mayora** | 2411081012 | System Analyst | [@jelmayora24-hub](https://github.com/jelmayora24-hub) |
| **Aprila Maulida** | 2411083002 | Quality Assurance | [@maulidaaprila12](https://github.com/maulidaaprila12) |

---

## 🛠️ Panduan Menjalankan Proyek (Bagi Anggota Tim)

Untuk menjalankan proyek ini di laptop masing-masing, ikuti langkah berikut:

### 1. Kloning Repository
```bash
git clone https://github.com/ahsanmadan/SaditaSystem.git
cd SaditaSystem
```

### 2. Install Dependency (Backend & Frontend)
```bash
composer install
npm install
```

### 3. Persiapan File Konfigurasi `.env`
Duplikat file yang sudah ada lalu *generate* kunci aplikasi:
```bash
cp .env.example .env
php artisan key:generate
```

### 4. Setup Database
Proyek sudah menggunakan SQLite by default. Langsung jalankan migrasi database:
```bash
php artisan migrate --seed
```

### 5. Jalankan Server
Buka dua terminal terpisah:
- **Terminal 1** (Backend): 
  ```bash
  php artisan serve
  ```
- **Terminal 2** (Frontend Auto-reload):
  ```bash
  npm run dev
  ```

Buka hasil akhirnya di web browser kesayanganmu: `http://localhost:8000` ✨

---
*Dikembangkan untuk Tugas Mata Kuliah Web Programming Framework - 2026*
