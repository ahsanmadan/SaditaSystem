# 🌸 Dokumentasi Master: SaditaSystem
*Sistem Manajemen Pesanan & Toko Digital untuk UMKM Florist (Padang)*

Dokumen ini merangkum seluruh perjalanan, keputusan teknis, fitur yang telah dibangun, serta aturan tata kelola (governance) tim dalam pengembangan **SaditaSystem**.

---

## 1. Identitas Proyek & Bisnis
*   **Nama Sistem**: SaditaSystem
*   **Klien/Mitra**: Sadita (Florist, Hantaran, & Dekorasi) yang berbasis di Padang, Sumatera Barat.
*   **Kontak Utama Mitra**: 
    *   WhatsApp: `0896-5309-0248`
    *   Instagram: `@sadita.florist`, `@sadita.hantaran`, `@sadita.decor`
*   **Tujuan Sistem**: Mendigitalkan proses manajemen pesanan, pelacakan pembayaran, serta menyediakan etalase publik (landing page) yang *premium* dan elegan untuk menarik pelanggan, menggantikan sistem manual.

---

## 2. Arsitektur & Teknologi (Tech Stack)
*   **Backend Framework**: Laravel 13.6
*   **Bahasa Pemrograman**: PHP 8.3
*   **Database**: MySQL
*   **Admin Panel**: Filament v5 (Menggunakan arsitektur Schema API terbaru).
*   **Frontend (Landing Page)**: Blade Templates + Tailwind CSS + Vanilla JS (untuk animasi & interaksi).
*   **Version Control & Hosting**: GitHub (Repository). Rencana *deployment* menggunakan Railway.app.

---

## 3. Fitur Utama yang Telah Dibangun

### A. Admin Panel (Filament)
Sistem *back-office* untuk mengelola operasional Sadita:
*   **Manajemen Pesanan (PesananResource)**: Tracking status pesanan dari awal masuk hingga selesai.
*   **Manajemen Pembayaran (PembayaranResource)**: 
    *   *Bug Fix Krusial*: Menghapus tombol "Create" manual (`CreateAction`) di halaman List Pembayaran. Pembayaran tidak boleh dibuat secara manual karena secara ketat membutuhkan `pesanan_id`. Pembayaran terikat secara otomatis dengan *lifecycle* pesanan.
*   **Dashboard & Analytics (KPI)**:
    *   Membangun `DashboardKpiService.php` untuk menampilkan ringkasan data (Total Omzet Hari Ini, Uang Masuk, Pesanan Pending).
    *   *Optimasi Performa*: Mengimplementasikan `Cache::remember()` dengan durasi 5 menit agar query dashboard tidak membebani database setiap kali halaman dimuat.
*   **Migrasi Schema API (Filament v5)**: Melakukan *refactoring* massal namespace dari `Filament\Forms\Components` menjadi `Filament\Schemas\Components` sesuai standar versi terbaru.

### B. Etalase Publik / Landing Page
Dibangun dengan fokus pada *User Experience* (UX) yang premium dan mewah (estetika yang memukau untuk pameran/ekshibisi).
*   **Visual & Aset Nyata**: Sepenuhnya menggunakan foto asli karya Sadita (bukan *dummy/placeholder*).
*   **Hero Section**: Desain *slider* gambar penuh (*full-screen*) dengan efek paralaks (*parallax*) yang disempurnakan (`transform-origin: center top`, `background-position: center 25%` agar subjek foto potret tidak terpotong).
*   **Kategori Produk**: 
    1.  **Papan Ucapan** (Standing Board & Mirror Premium)
    2.  **Hantaran** (Seserahan & Gift Box)
    3.  **Dekorasi** (Event Decoration)
    *Catatan: Kategori "Bucket Bunga" resmi dihapus berdasarkan instruksi terbaru.*
*   **Grid Layout**: Menggunakan tata letak 3 kolom yang seimbang dan elegan di tengah halaman (`max-w-5xl`, `grid-cols-3`).
*   **Galeri Masonry**: Menampilkan 23 foto *real* hasil karya Sadita dalam tata letak *masonry* campuran (tinggi, lebar, normal) agar dinamis dan tidak membosankan.
*   **Integrasi WhatsApp Pintar**: Setiap tombol "Hubungi Kami" pada kartu produk mengarah langsung ke WhatsApp resmi Sadita dengan *template* pesan otomatis yang menyertakan nama produk yang diklik.
*   **Ikon Premium**: Mengganti emoji teks standar di bagian "Kenapa Pilih Sadita?" dengan ikon SVG dari **Heroicons Outline**, diwarnai aksen *Soft Gold* (`#E8C87A`) untuk kesan eksklusif.

---

## 4. Tata Kelola Tim & Aturan (Governance Workflow)

Untuk menjaga agar kode tetap rapi, bebas konflik besar, dan *deploy-ready*, kita telah menetapkan standar kerja ketat yang diatur langsung di GitHub (Branch Protection) dan dicatat dalam `panduan_alur_kerja_tim.md`.

### Struktur Branch
*   `main`: Branch khusus *Production*. Kode di sini harus 100% jalan, stabil, dan siap pakai pelanggan. Tidak boleh disentuh langsung.
*   `develop`: Branch integrasi (*Staging*). Titik temu semua fitur dari semua *developer* sebelum masuk ke `main`.
*   `feature/*` atau `fix/*`: Branch kerja personal. Di sinilah *developer* mengetik kode.

### Aturan Wajib Bekerja (Untuk Semua Anggota: Bagas, Maulid, dll)
1.  **DILARANG NGODING DI DEVELOP**: Jangan pernah mengetik baris kode apa pun saat berada di branch `develop`.
2.  **Selalu Buat Branch Baru**: Tarik *update* terbaru dari `develop`, lalu buat branch baru (contoh: `git checkout -b feature/nama-tugas`).
3.  **Pesan Commit Terstruktur (Conventional Commits)**:
    *   `feat: ...` (Menambah fitur baru)
    *   `fix: ...` (Memperbaiki bug/error)
    *   `style: ...` (Perbaikan UI/CSS tanpa ubah logika)
    *   `refactor: ...` (Merapikan kode backend)
    *   `chore: ...` (Update library, env, konfigurasi)
4.  **Wajib Pull Request (PR)**: Setelah selesai, *push* branch ke GitHub dan buka PR menuju `develop`.
5.  **Review & Approval Wajib**: Berdasarkan GitHub Rulesets yang kita pasang, branch `develop` **dikunci**. Sebuah PR TIDAK BISA digabung (*merge*) kalau belum di-review dan di-Approve oleh Lead (Ahsan) atau Maulid. Tidak ada istilah "*force push*" atau menerobos aturan. (Kecuali Anda yang memiliki bypass role sebagai Admin).

---

## 5. Status Proyek Saat Ini & Langkah Selanjutnya

**Status Saat Ini:**
*   Sistem berjalan mulus di *localhost*.
*   Landing page sudah dipoles menjadi versi *Premium* (Visual *real*, ikon SVG, layout 3 kolom).
*   Branch terakhir (`feature/frontend-homepage-polish`) sedang menunggu di-*merge* ke `develop`.

**To-Do List (Langkah Selanjutnya):**
1.  **Merge PR**: Menggabungkan hasil *polish homepage* ke `develop`.
2.  **Deployment (Prioritas Tertinggi)**: Mengunggah aplikasi ke **Railway.app** agar sistem memiliki *link* aktif (URL publik).
3.  **Generate QR Code**: Mengambil URL hasil deployment Railway untuk dibuatkan QR Code yang akan ditempel di poster pameran.
4.  **Tunggakan Minor (Post-Deployment)**:
    *   Menambahkan bagian *Testimonial* pelanggan di *landing page*.
    *   Menyelesaikan tugas matkul `APPL-07` terkait mockup di Figma.

---

> ✨ **Kesimpulan**: SaditaSystem bukan sekadar tugas kuliah biasa. Kita sedang membangun aplikasi *enterprise-grade* dengan standar industri nyata — mulai dari keamanan *database*, desain estetik yang mahal, hingga alur *version control* sekelas *tech startup* profesional.
