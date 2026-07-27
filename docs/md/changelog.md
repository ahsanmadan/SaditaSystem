# Changelog - SaditaSystem

Dokumen ini merangkum progres penting project yang sudah dikerjakan tim selama proses implementasi SaditaSystem.

Changelog ini bersifat berjalan dan akan terus diperbarui mengikuti perkembangan fitur, perbaikan bug, penyesuaian dokumentasi, serta hasil evaluasi implementasi.

## 2026-06-30

- merapikan landing page public agar tampilan lebih konsisten dengan arah visual project
- memperbaiki section kategori, alasan memilih layanan, cara pesan, loading screen, dan beberapa interaksi hover
- menambahkan ikon kustom untuk alur cara pesan pada public website
- memperbaiki anchor kategori seperti `#kategori-papan-ucapan` agar kembali mengarah ke section yang benar
- memperbarui dokumentasi `github-action-doc.md` agar sinkron dengan workflow CI yang ada di repository
- memperbarui dokumentasi `refactoring-doc.md` agar lebih sesuai dengan kondisi kode dan branch saat ini

## 2026-06-09

- menambahkan fitur kode promo ke dalam alur pesanan
- menambahkan penanda promo expired di admin panel
- mulai menggabungkan fitur promo ke branch pengembangan utama

## 2026-06-08

- menambahkan integrasi checkout pembayaran menggunakan DOKU
- menambahkan penyesuaian admin untuk mendukung alur pembayaran dan aksi terkait
- mulai menghubungkan flow pembayaran publik dengan proses verifikasi yang lebih terstruktur

## 2026-06-07

- merapikan dokumentasi project untuk kebutuhan penilaian
- menambahkan README utama yang sesuai dengan isi repo
- memisahkan installation doc, feature doc, dependency doc, refactoring doc, dan github action doc
- menambahkan screenshot project ke README

## 2026-06-03

- memperbaiki flow submit order agar waktu pengiriman tidak lagi gagal saat memakai input normal dari UI
- memverifikasi submit order lewat browser sampai berhasil masuk ke invoice

## 2026-05-25

- melakukan QA menyeluruh pada public web, login admin, admin panel, dan flow order
- mengidentifikasi bug utama pada order, invoice, testing, dan beberapa isu UI/UX

## 2026-05-19

- menyesuaikan stack frontend dan build agar memakai Vite + Tailwind CSS 4
- menyiapkan pipeline build lokal melalui `npm run build`

## 2026-05-18

- menyiapkan arah revisi ERD dan schema untuk mendukung tipe layanan sewa, jasa, dan hantaran
- menambahkan draft migration untuk flow order dan staged pricing

## 2026-05-02

- merapikan `.gitignore`
- memasukkan `AGENTS.md` ke repo sebagai panduan kerja agent dan konteks tim

## Catatan

Changelog ini adalah ringkasan progres penting yang relevan untuk presentasi dan dokumentasi proyek. Riwayat commit detail tetap dapat dilihat di repository GitHub, sedangkan dokumen ini dipakai untuk merangkum perkembangan implementasi dalam bentuk yang lebih mudah dibaca.
