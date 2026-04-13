<div align="center">

# 🌸 SaditaSystem

### Sistem Informasi E-Commerce & Manajemen Dekorasi Berbasis Web

[![Laravel](https://img.shields.io/badge/Laravel-12.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://php.net)
[![Groq API](https://img.shields.io/badge/Groq_API-LLM_Chatbot-F55036?style=for-the-badge&logo=groq&logoColor=white)](https://groq.com)
[![License](https://img.shields.io/badge/License-MIT-green?style=for-the-badge)](LICENSE)
[![SDGs](https://img.shields.io/badge/SDGs-Poin_8-E5243B?style=for-the-badge)](https://sdgs.un.org/goals/goal8)

<br/>

> **Mitra UMKM:** Sadita Decoration — Padang, Sumatera Barat 🏝️  
> Platform digital terpadu untuk mengelola pemesanan, inventaris, dan layanan ketiga unit bisnis Sadita dalam satu dashboard.

<br/>

![-----](https://raw.githubusercontent.com/andreasbm/readme/master/assets/lines/colored.png)

</div>

## 📌 Tentang Proyek

**SaditaSystem** adalah aplikasi web yang dikembangkan sebagai solusi digitalisasi bagi UMKM **Sadita Decoration**, sebuah usaha jasa sewa dekorasi dan pernikahan yang berlokasi di Kota Padang, Sumatera Barat. Proyek ini merupakan bagian dari mata kuliah **Pemrograman Web Framework** dan terintegrasi lintas 7 mata kuliah sebagai Proyek Berbasis Pembelajaran (PBL).

Saat ini Sadita Decoration masih mengelola pemesanan secara manual melalui WhatsApp dan Instagram, yang rawan terjadi *double booking*, kehilangan data pesanan, dan kesulitan memantau stok. SaditaSystem hadir untuk menjawab tantangan tersebut.

**Tema SDGs:** Poin 8 — *Decent Work and Economic Growth* (Target 8.3 & 8.10)

---

## 🏢 Unit Bisnis Mitra

| Unit | Instagram | Layanan |
|------|-----------|---------|
| Sadita Decor | [@sadita.decor](https://instagram.com/sadita.decor) | Birthday · Engagement · Grand Opening · Aqiqah |
| Sadita Hantaran | [@sadita.hantaran](https://instagram.com/sadita.hantaran) | Seserahan · Mahar · Ring Box · Hampers · Souvenir |
| Sadita Florist | [@sadita.florist](https://instagram.com/sadita.florist) | Floral Arrangement · Bouquet · Event Floristry |

---

## ✨ Fitur Utama

### 🌐 Base Web (Lead Programmer)
- **Autentikasi** — Registrasi & login pelanggan dan admin
- **Katalog Produk** — Tampilan produk & paket dari ketiga unit bisnis
- **Sistem Booking Online** — Form pemesanan dengan kalender ketersediaan real-time
- **Dashboard Admin** — Manajemen pesanan, stok inventaris, dan data pelanggan
- **Laporan Keuangan** — Rekap pemasukan, DP, pelunasan per bulan
- **Notifikasi** — Status pesanan via WhatsApp API & Email

### 🤖 Fitur AI (AI Specialist)
- **Rekomendasi Paket** — Content-Based Filtering berdasarkan preferensi & budget pelanggan
- **Chatbot LLM** — Chatbot berbasis Groq API (Llama/Mixtral) untuk FAQ & panduan booking
- **Prediksi Jadwal Sibuk** — Analisis historis pemesanan untuk antisipasi stok

### 🧪 Quality Assurance
- Black Box Testing seluruh modul
- Pengujian performa simulasi 50+ pengguna
- User Acceptance Testing (UAT) bersama mitra Sadita
- Dokumentasi bug setiap milestone

---

## 🛠️ Tech Stack

| Kategori | Teknologi |
|----------|-----------|
| Backend | Laravel 12 (PHP 8.2+) |
| Frontend | Blade Templates + JavaScript |
| Database | MySQL |
| AI / LLM | Groq API (Llama 3 / Mixtral) |
| Notifikasi | WhatsApp API (Fonnte) + SMTP Email |
| Version Control | Git + GitHub |
| Project Management | Trello (Kanban) |
| Hosting | VPS / Shared Hosting |

---

## 🚀 Cara Setup Lokal

### Prasyarat
- PHP >= 8.2
- Composer
- MySQL
- Node.js & NPM
- Git

### Instalasi

```bash
# 1. Clone repository
git clone https://github.com/[username]/saditasystem.git
cd saditasystem

# 2. Install dependencies PHP
composer install

# 3. Install dependencies Node.js
npm install

# 4. Salin file environment
cp .env.example .env

# 5. Generate application key
php artisan key:generate

# 6. Konfigurasi .env (database, mail, Groq API key, dll)
# Edit file .env sesuai konfigurasi lokal

# 7. Jalankan migrasi database
php artisan migrate --seed

# 8. Build assets frontend
npm run dev

# 9. Jalankan server lokal
php artisan serve
```

Aplikasi akan berjalan di `http://localhost:8000`

---

## ⚙️ Konfigurasi Environment

Salin `.env.example` ke `.env` dan isi variabel berikut:

```env
APP_NAME=SaditaSystem
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=saditasystem
DB_USERNAME=root
DB_PASSWORD=

# Groq API (AI Chatbot & Rekomendasi)
GROQ_API_KEY=your_groq_api_key_here
GROQ_MODEL=llama3-8b-8192

# WhatsApp API (Fonnte)
FONNTE_TOKEN=your_fonnte_token_here

# Mail
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your_email@gmail.com
MAIL_PASSWORD=your_app_password
```

> ⚠️ **Jangan pernah commit file `.env` ke repository!** Pastikan sudah masuk di `.gitignore`.

---

## 🌿 Git Workflow

Kami menggunakan **Git Flow** dengan struktur branch sebagai berikut:

```
main          ← Production (stabil, dipakai saat demo milestone)
│
└── develop   ← Staging (integrasi semua fitur)
    │
    ├── feature/[nama-fitur]   ← Fitur baru
    ├── fix/[nama-bug]         ← Perbaikan bug
    ├── test/[nama-pengujian]  ← Testing & QA
    └── docs/[nama-dokumen]    ← Dokumentasi
```

### Alur Kontribusi

```bash
# 1. Ambil update terbaru dari develop
git checkout develop
git pull origin develop

# 2. Buat branch baru sesuai fitur
git checkout -b feature/nama-fitur

# 3. Kerjakan fitur, lalu commit
git add .
git commit -m "feat: deskripsi singkat perubahan"

# 4. Push ke GitHub
git push origin feature/nama-fitur

# 5. Buat Pull Request → develop
#    Assign ke Lead Programmer untuk review
```

### Format Commit Message

```
feat:     fitur baru
fix:      perbaikan bug
refactor: perubahan struktur kode
test:     penambahan/perbaikan test
docs:     perubahan dokumentasi
style:    perubahan formatting (tanpa mengubah logika)
chore:    update dependency, konfigurasi
```

### Aturan Penting
- ❌ **Dilarang** push langsung ke `main`
- ✅ Semua perubahan harus melalui **Pull Request**
- ✅ Minimal **1 reviewer** (Lead Programmer) sebelum merge ke `develop`
- ✅ Minimal **2 commit per minggu** pada fase aktif pengembangan
- ✅ Hapus branch fitur setelah berhasil di-merge

---

## 📋 Backlog & Manajemen Proyek

Semua tugas dan progres sprint dikelola di **Trello Board**:  
🔗 [PBL2026B_SaditaSystem — Trello](https://trello.com)

Konvensi penamaan card:
```
[KODE_MATKUL]-[NO] Nama Aktivitas
Contoh: PWF-04: Sistem Booking Online & Kalender Ketersediaan
```

---

## 📅 Milestone

| Milestone | Deadline | Target |
|-----------|----------|--------|
| M1 — Inisiasi | Minggu 4 | Project Charter, WBS, SRS, Wawancara Mitra |
| M2 — UTS | Minggu 8 | Prototype AI, Wireframe UI, ERD, Demo Chatbot |
| M3 — Beta | Minggu 13 | Beta live on hosting, UAT, Fitur AI terintegrasi |
| M4 — UAS | Minggu 16 | Demo Day, Laporan Akhir, Source Code Final |

---

## 👥 Tim Pengembang

**Kelompok 5 — PBL 2026B**

| Nama | NIM | Peran |
|------|-----|-------|
| Bagastio Putra Joandri | 2411081005 | Project Manager |
| Ahsan Ramadan | 2411081002 | System Analyst & Lead Programmer |
| Jeli Mayora | 2411081012 | AI Specialist |
| Aprila Maulida | 2411083002 | Quality Assurance |

**Dosen Pengampu:**
- Rayendra, S.T., M.Kom. — Pemrograman Web Framework
- Eko Purnomo, S.Ds, M.Sn. — Komunikasi Bisnis
- *(dan dosen mata kuliah lainnya)*

---

## 📄 Lisensi

Proyek ini dikembangkan untuk keperluan akademik.  
© 2025 Sadita System — Kelompok 5, Universitas Bung Hatta / [nama universitas]

---

<div align="center">

*"Digitalisasi UMKM, Satu Langkah untuk Pertumbuhan Ekonomi Lokal"* 🌱

</div>
