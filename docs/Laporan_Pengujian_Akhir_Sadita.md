# LAPORAN AKHIR PENGUJIAN PERANGKAT LUNAK (TESTING REPORT)
## SISTEM INFORMASI MANAJEMEN BISNIS SADITA DECORATION
### Metode Pengujian: White Box (Statement Coverage) & Black Box (Automated & Manual)

---

## 1. PENDAHULUAN

### 1.1 Latar Belakang
Pengujian perangkat lunak ini dilakukan untuk memastikan keandalan, keamanan, dan fungsionalitas Sistem Informasi Manajemen Bisnis **Sadita Decoration** yang mencakup modul Pemesanan (Sewa, Jasa, Hybrid), Admin Panel (Filament), Autentikasi Admin, dan Pelacakan Pesanan (Order Tracking). Pengujian dibagi menjadi dua metode utama:
1. **White Box Testing (Statement Coverage)**: Menganalisis kebenaran baris kode (statements) internal Laravel pada Controller utama (`HomeController`, `AuthController`, dan `OrderController`) untuk memastikan tidak ada *dead code* dan seluruh percabangan if-else dieksekusi minimal satu kali.
2. **Black Box Testing**: Menguji fungsionalitas antarmuka dan alur bisnis dari sudut pandang pengguna menggunakan skenario manual dan otomatisasi (**Automated Testing**) dengan **Java, Maven, dan Selenium WebDriver**.

### 1.2 Lingkup Pengujian
Modul-modul yang diuji meliputi:
- **Landing Page & Galeri**: Filter kategori produk, scroll produk.
- **Pemesanan Mandiri (Guest/No-Login)**: Pengisian formulir pemesanan, konvalidasi data, dan generate invoice.
- **Pelacakan Pesanan**: Pencarian status pesanan menggunakan kode pesanan (`SDT-`).
- **Autentikasi Admin**: Login, logout, dan pengalihan ke dashboard admin panel.

---

## 2. SKENARIO PENGUJIAN WHITE BOX (STATEMENT COVERAGE)

Statement Coverage menjamin setiap baris kode eksekusi diuji minimal satu kali. Berikut adalah dokumentasi lengkap skenario pengujian White Box yang dipetakan berdasarkan pembagian kerja tim.

### 2.1 Skenario Pengujian – Bagatio Putra Joandri (WB-BPJ)

| No | ID Skenario | Komponen / Fungsi | Jalur Pernyataan Teruji (Statement Coverage) | Data Masukan (Input Data) | Hasil Diharapkan | Status |
|----|-------------|-------------------|----------------------------------------------|----------------------------|------------------|--------|
| 1 | WB-BPJ-001 | OrderController @store | Baris 18: `preg_replace` untuk membersihkan format harga kotor dari simbol mata uang. | `$request->price = 'Rp 250.000'` | `$rawPrice = '250000'`, regex berhasil membuang simbol 'Rp' dan titik pembatas ribuan. | LULUS |
| 2 | WB-BPJ-002 | OrderController @store | Baris 19: Ternary operator pengkondisian harga jika kosong / null. | `$request->price = ''` (kosong) | `$numericPrice = 0`, default fallback nilai integer berhasil dijalankan. | LULUS |
| 3 | WB-BPJ-003 | OrderController @store | Baris 22-28: `firstOrCreate()` mengeksekusi pencarian Pelanggan lama yang terdaftar di database. | `$request->sender_phone = '08123456789'` | `firstOrCreate` mengembalikan record Pelanggan lama, tidak membuat baris baru di database. | LULUS |
| 4 | WB-BPJ-004 | OrderController @store | Baris 22-28: `firstOrCreate()` membuat record Pelanggan baru jika nomor belum terdaftar. | `$request->sender_phone = '0899888777'`, `$request->sender_name = 'Dewi'` | Record Pelanggan baru tersimpan di database dengan nama 'Dewi' dan email bernilai null. | LULUS |
| 5 | WB-BPJ-005 | OrderController @store | Baris 31: Pembentukan string `kode_pesanan` unik dengan pola prefix 'SDT-'. | Data input pemesanan valid | Variabel `$kodePesanan` terisi string format `SDT-YYYYMMDD-[5 karakter acak]` secara dinamis. | LULUS |
| 6 | WB-BPJ-006 | OrderController @store | Baris 32-41: Blok pembuatan record Pesanan dengan total_harga valid. | `$numericPrice = 300000`, `$request->special_instruction = 'Kirim pagi'` | Record Pesanan sukses tersimpan di database dengan total_harga, biaya_ongkir, grand_total = 300000. | LULUS |
| 7 | WB-BPJ-007 | OrderController @store | Baris 39: Perhitungan `batas_waktu_bayar` (menambahkan 24 jam dari sekarang). | Request pemesanan disubmit | Kolom `batas_waktu_bayar` terisi waktu sekarang + 24 jam dengan presisi menit dan detik. | LULUS |
| 8 | WB-BPJ-008 | OrderController @store | Baris 40: Penyimpanan instruksi khusus pembeli pada kolom `catatan_pembeli`. | `$request->special_instruction = 'Bunga merah'` | Kolom `catatan_pembeli` pada tabel pesanan terisi teks instruksi yang dikirimkan pembeli. | LULUS |
| 9 | WB-BPJ-009 | OrderController @store | Baris 40: Penyimpanan `catatan_pembeli` jika tidak ada instruksi khusus dari pembeli. | `$request->special_instruction = null` | Kolom `catatan_pembeli` pada database terisi dengan nilai null secara eksplisit. | LULUS |
| 10 | WB-BPJ-010 | OrderController @store | Baris 35: Inisialisasi status pesanan awal dengan string 'menunggu_pembayaran'. | Request pemesanan valid disubmit | Kolom `status` pada record pesanan terisi string 'menunggu_pembayaran' sebagai status awal default. | LULUS |

### 2.2 Skenario Pengujian – Ahsan Ramadan (WB-AR)

| No | ID Skenario | Komponen / Fungsi | Jalur Pernyataan Teruji (Statement Coverage) | Data Masukan (Input Data) | Hasil Diharapkan | Status |
|----|-------------|-------------------|----------------------------------------------|----------------------------|------------------|--------|
| 1 | WB-AR-011 | OrderController @store | Baris 44: Pencarian produk yang valid dan terdaftar di database. | `$request->product_name = 'Papan Bunga Grand Opening'` | `$produk` ditemukan dan variabel `$produkId` diisi dengan ID produk tersebut. | LULUS |
| 2 | WB-AR-012 | OrderController @store | Baris 45: Fallback pencarian produk pertama jika nama produk tidak ditemukan. | `$request->product_name = 'Produk Palsu'` | `$produk` bernilai null, `$produkId` fallback diisi dengan ID dari `Produk::first()->id` atau 1. | LULUS |
| 3 | WB-AR-013 | OrderController @store | Baris 48-57: Pembuatan record DetailPesanan dan snapshots data produk. | `$request->product_name = 'Papan Bunga'`, `$request->greeting_msg = 'Selamat'` | Record DetailPesanan dibuat dengan `nama_produk_snapshot = 'Papan Bunga'` dan `teks_ucapan = 'Selamat'`. | LULUS |
| 4 | WB-AR-014 | OrderController @store | Baris 51: Fallback `nama_produk_snapshot` jika parameter nama kosong. | `$request->product_name = null` | Kolom `nama_produk_snapshot` terisi string default 'Produk Sadita'. | LULUS |
| 5 | WB-AR-015 | OrderController @store | Baris 60-65: Pemetaan jam pengiriman untuk opsi label waktu Pagi. | `$request->delivery_time = 'Pagi (08:00 - 12:00)'` | Variabel `$jamPengiriman` terisi nilai '08:00:00' berdasarkan pemetaan `$timeMap`. | LULUS |
| 6 | WB-AR-016 | OrderController @store | Baris 60-65: Pemetaan jam pengiriman untuk opsi label waktu Siang. | `$request->delivery_time = 'Siang (12:00 - 16:00)'` | Variabel `$jamPengiriman` terisi nilai '12:00:00' berdasarkan pemetaan `$timeMap`. | LULUS |
| 7 | WB-AR-017 | OrderController @store | Baris 60-65: Pemetaan jam pengiriman untuk opsi label waktu Sore. | `$request->delivery_time = 'Sore (16:00 - 20:00)'` | Variabel `$jamPengiriman` terisi nilai '16:00:00' berdasarkan pemetaan `$timeMap`. | LULUS |
| 8 | WB-AR-018 | OrderController @store | Baris 69: Pembuatan record Pengiriman dengan nama penerima sama dengan nama pengirim jika kosong. | `$request->receiver_name = null`, `$request->sender_name = 'Ahmad'` | Record Pengiriman tersimpan dengan `nama_penerima = 'Ahmad'` (fallback dari pengirim). | LULUS |
| 9 | WB-AR-019 | OrderController @store | Baris 71: Fallback alamat pengiriman jika bernilai kosong/null. | `$request->address = null` | Kolom `alamat_lengkap` pada tabel pengirimans bernilai default 'Ambil di Toko'. | LULUS |
| 10 | WB-AR-020 | OrderController @store | Baris 78-79: Aksi redirect halaman sukses dengan pengiriman flash data. | Data request disubmit secara lengkap dan valid | Redirect ke route 'invoice.show' dengan param order_id kode pesanan baru dan session success. | LULUS |

### 2.3 Skenario Pengujian – Jeli Mayora (WB-JM)

| No | ID Skenario | Komponen / Fungsi | Jalur Pernyataan Teruji (Statement Coverage) | Data Masukan (Input Data) | Hasil Diharapkan | Status |
|----|-------------|-------------------|----------------------------------------------|----------------------------|------------------|--------|
| 1 | WB-JM-021 | AuthController @showLogin | Baris 12: Pengembalian view login panel admin. | GET request ke halaman '/login' | Mengembalikan view login, status HTTP 200 berhasil merender antarmuka halaman. | LULUS |
| 2 | WB-JM-022 | AuthController @login | Baris 17-20: Validasi field email dan password wajib diisi (required). | POST /login dengan email = '' dan password = '' | Sistem memicu ValidationException, menolak request dan mengembalikan error input. | LULUS |
| 3 | WB-JM-023 | AuthController @login | Baris 22-26: Login admin sukses dengan kredensial terdaftar & regenerasi session. | email = 'admin@sadita.com', password = 'admin123' (valid) | Auth::attempt sukses, session()->regenerate() dieksekusi, redirect ke halaman '/admin'. | LULUS |
| 4 | WB-JM-024 | AuthController @login | Baris 29-31: Login gagal dengan kredensial tidak cocok dan return error email. | email = 'admin@sadita.com', password = 'salahpassword' | Auth::attempt gagal, redirect back dengan errors kredensial tidak cocok dan retain input email. | LULUS |
| 5 | WB-JM-025 | AuthController @logout | Baris 36-41: Logout admin, pembersihan session dan token csrf. | POST /logout dengan status admin terautentikasi | Auth::logout() dijalankan, session di-invalidate, token session di-regenerate, redirect ke '/'. | LULUS |
| 6 | WB-JM-026 | OrderController @normalizeDeliveryTime | Penanganan parameter deliveryTime jika kosong/null (inline/method). | `$deliveryTime = null` | Mengembalikan string waktu default '09:00:00'. | LULUS |
| 7 | WB-JM-027 | OrderController @normalizeDeliveryTime | Pemetaan waktu untuk string label 'Pagi (08:00 - 12:00)'. | `$deliveryTime = 'Pagi (08:00 - 12:00)'` | Mengembalikan string waktu '08:00:00' berdasarkan pemetaan `$timeMap`. | LULUS |
| 8 | WB-JM-028 | OrderController @normalizeDeliveryTime | Pemetaan waktu untuk string label 'Siang (12:00 - 16:00)'. | `$deliveryTime = 'Siang (12:00 - 16:00)'` | Mengembalikan string waktu '12:00:00' berdasarkan pemetaan `$timeMap`. | LULUS |
| 9 | WB-JM-029 | OrderController @normalizeDeliveryTime | Pemetaan waktu untuk string label 'Sore (16:00 - 20:00)'. | `$deliveryTime = 'Sore (16:00 - 20:00)'` | Mengembalikan string waktu '16:00:00' berdasarkan pemetaan `$timeMap`. | LULUS |
| 10 | WB-JM-030 | OrderController @normalizeDeliveryTime | Fallback waktu jika string tidak sesuai format dan label map. | `$deliveryTime = 'malam hari'` | Mengembalikan default '09:00:00'. | LULUS |

### 2.4 Skenario Pengujian – Aprilla Maulida (WB-AM)

| No | ID Skenario | Komponen / Fungsi | Jalur Pernyataan Teruji (Statement Coverage) | Data Masukan (Input Data) | Hasil Diharapkan | Status |
|----|-------------|-------------------|----------------------------------------------|----------------------------|------------------|--------|
| 1 | WB-AM-031 | HomeController @index | Baris 12: Filter Kategori aktif (where is_aktif = true). | Database memiliki kategori aktif dan tidak aktif | Hanya record Kategori dengan is_aktif = true yang diambil dari database. | LULUS |
| 2 | WB-AM-032 | HomeController @index | Baris 13-16: Eager load produk aktif dan pengurutan harga terendah. | Kategori terhubung dengan produk aktif dan non-aktif | daftarProduk memuat produk aktif (is_aktif = true) yang diurutkan menaik berdasarkan harga_dasar. | LULUS |
| 3 | WB-AM-033 | HomeController @index | Baris 16: Pembatasan jumlah produk maksimal 8 item per kategori. | Kategori memiliki 10 produk aktif di database | daftarProduk pada kategori dipotong hingga tersisa maksimal 8 produk saja menggunakan limit/take(8). | LULUS |
| 4 | WB-AM-034 | HomeController @index | Baris 21: Aksi return view beranda dengan data kategoris. | GET request ke halaman beranda '/' | Mengembalikan view 'pages.home.index' beserta variabel `$kategoris`. | LULUS |
| 5 | WB-AM-035 | OrderController @show | Baris 85-87: Eager loading data pelanggan, detailItems, dan pengiriman berdasarkan kode pesanan. | `$order_id = 'SDT-20260608-ABCDE'` (valid) | Query memuat detail pesanan dengan relasinya dari database, return view invoice dengan snapToken = null. | LULUS |
| 6 | WB-AM-036 | OrderController @show | Baris 87: `firstOrFail()` melempar ModelNotFoundException jika kode pesanan tidak terdaftar. | `$order_id = 'SDT-INVALID-CODE'` | `firstOrFail` melempar ModelNotFoundException, sistem merender halaman error HTTP 404 (Not Found). | LULUS |
| 7 | WB-AM-037 | OrderController @track | Baris 96: Pencarian pesanan untuk dilacak di database. | `$order_id = 'SDT-20260608-ABCDE'` (valid) | Query database berhasil menemukan pesanan berdasarkan kode pesanan. | LULUS |
| 8 | WB-AM-038 | OrderController @track | Baris 99: Penggabungan nama-nama produk snapshot dengan `implode`. | DetailPesanan memiliki item 'Papan Bunga Pernikahan' dan 'Sewa Box Hantaran' | Variabel `$productNames` terisi string gabungan 'Papan Bunga Pernikahan, Sewa Box Hantaran'. | LULUS |
| 9 | WB-AM-039 | OrderController @track | Baris 100-106: Pemetaan status internal database ke status publik. | Pesanan di database berstatus 'menunggu_pembayaran' | Nilai status yang dikembalikan dalam JSON terpetakan menjadi 'UNPAID'. | LULUS |
| 10 | WB-AM-040 | OrderController @track | Baris 120: Pengembalian JSON error jika kode pesanan tidak ditemukan. | `$order_id = 'SDT-PALSUS'` | Mengembalikan response JSON `['found' => false]` dengan status kode HTTP 404. | LULUS |

---

## 3. PENGGALAN KODE PADA PENGUJIAN WHITE BOX (CODE SNIPPETS)

### 3.1 OrderController - Parsing & Penyimpanan Data Pesanan (`@store`)
Mencakup skenario **WB-BPJ-001** sampai **WB-BPJ-010** dan **WB-AR-011** sampai **WB-AR-020**:
```php
public function store(Request $request)
{
    // WB-BPJ-001 & WB-BPJ-002: Pembersihan format rupiah & fallback 0
    $rawPrice = preg_replace('/[^0-9]/', '', $request->price);
    $numericPrice = $rawPrice ? (int) $rawPrice : 0;

    // WB-BPJ-003 & WB-BPJ-004: firstOrCreate untuk Pelanggan (Baru atau Lama)
    $pelanggan = Pelanggan::firstOrCreate(
        ['no_hp' => $request->sender_phone],
        [
            'nama_lengkap' => $request->sender_name,
            'email' => null
        ]
    );

    // WB-BPJ-005 - WB-BPJ-010: Generate Kode Pesanan & insert data Pesanan
    $kodePesanan = 'SDT-' . date('Ymd') . '-' . strtoupper(Str::random(5));
    $pesanan = Pesanan::create([
        'pelanggan_id' => $pelanggan->id,
        'kode_pesanan' => $kodePesanan,
        'status' => 'menunggu_pembayaran', // Status Awal
        'total_harga' => $numericPrice,
        'biaya_ongkir' => 0,
        'grand_total' => $numericPrice,
        'batas_waktu_bayar' => now()->addHours(24), // Batas Bayar 24 Jam
        'catatan_pembeli' => $request->special_instruction,
    ]);

    // WB-AR-011 & WB-AR-012: Pencarian produk atau fallback
    $produk = Produk::where('nama', $request->product_name)->first();
    $produkId = $produk ? $produk->id : Produk::first()->id ?? 1;

    // WB-AR-013 & WB-AR-014: Detail Pesanan Snapshot
    DetailPesanan::create([
        'pesanan_id' => $pesanan->id,
        'produk_id' => $produkId,
        'nama_produk_snapshot' => $request->product_name ?? 'Produk Sadita',
        'harga_satuan_snapshot' => $numericPrice,
        'kuantitas' => 1,
        'subtotal' => $numericPrice,
        'teks_ucapan' => $request->greeting_msg,
        'referensi_desain' => null,
    ]);

    // WB-AR-015 - WB-AR-017 & WB-JM-026 - WB-JM-030: Pemetaan Waktu Pengiriman
    $timeMap = [
        'Pagi (08:00 - 12:00)' => '08:00:00',
        'Siang (12:00 - 16:00)' => '12:00:00',
        'Sore (16:00 - 20:00)' => '16:00:00',
    ];
    $jamPengiriman = $timeMap[$request->delivery_time] ?? '09:00:00';

    // WB-AR-018 & WB-AR-019: Pembuatan record Pengiriman & Fallbacks
    Pengiriman::create([
        'pesanan_id' => $pesanan->id,
        'nama_penerima' => $request->receiver_name ?? $request->sender_name,
        'no_hp_penerima' => $request->sender_phone,
        'alamat_lengkap' => $request->address ?? 'Ambil di Toko',
        'patokan_lokasi' => null,
        'tanggal_pengiriman' => $request->delivery_date ?? now()->toDateString(),
        'jam_pengiriman' => $jamPengiriman,
        'status' => 'menunggu_jadwal',
    ]);

    // WB-AR-020: Redirect Halaman Sukses
    return redirect()->route('invoice.show', ['order_id' => $kodePesanan])
        ->with('success', 'Pesanan berhasil dibuat.');
}
```

### 3.2 AuthController - Login & Session Setup (`@login`)
Mencakup skenario **WB-JM-021** sampai **WB-JM-025**:
```php
public function login(Request $request)
{
    // WB-JM-022: Validasi input wajib diisi
    $credentials = $request->validate([
        'email' => ['required', 'string'],
        'password' => ['required', 'string'],
    ]);

    // WB-JM-023: Autentikasi berhasil & regenerasi session
    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();
        return redirect()->intended('/admin');
    }

    // WB-JM-024: Autentikasi gagal
    return back()->withErrors([
        'email' => 'Kredensial yang diberikan tidak cocok dengan catatan kami.',
    ])->onlyInput('email');
}
```

### 3.3 HomeController - Eager Loading & Batas Produk (`@index`)
Mencakup skenario **WB-AM-031** sampai **WB-AM-034**:
```php
public function index()
{
    // WB-AM-031: filter is_aktif pada Kategori
    $kategoris = Kategori::where('is_aktif', true)
        // WB-AM-032 & WB-AM-033: filter & limit 8 produk per kategori
        ->with(['daftarProduk' => function ($q) {
            $q->where('is_aktif', true)
              ->orderBy('harga_dasar', 'asc')
              ->limit(8);
        }])
        ->orderBy('id')
        ->get();

    // WB-AM-034: Mengembalikan view halaman utama
    return view('pages.home.index', compact('kategoris'));
}
```

---

## 4. SKENARIO PENGUJIAN BLACK BOX & AUTOMATED TESTING (SELENIUM)

Pengujian fungsionalitas antarmuka (Black Box) dikembangkan dalam suite tes otomatis berbasis **Selenium WebDriver** (Java & Maven). Berikut adalah daftar skenario uji fungsionalitas:

### 4.1 Tabel Pengujian Fungsionalitas Black Box

| No | ID Test Case | Fitur Teruji | Deskripsi Skenario / Langkah | Data Masukan (Input) | Hasil Diharapkan | Hasil Aktual | Status |
|----|--------------|--------------|------------------------------|-----------------------|------------------|--------------|--------|
| 1 | TC-01 | Autentikasi Admin | Login admin dengan kredensial valid dan centang robot. | `Username: admin`, `Password: admin`, `verify_bot: true` | Masuk ke panel admin (`/admin`). | Berhasil masuk ke `/admin`. | PASS |
| 2 | TC-02 | Autentikasi Admin | Login admin dengan password yang salah. | `Username: admin`, `Password: salah`, `verify_bot: true` | Menampilkan pesan error kredensial tidak cocok. | Error ditampilkan, tetap di `/login`. | PASS |
| 3 | TC-03 | Autentikasi Admin | Login admin dengan mengosongkan input. | `Username: `, `Password: ` | HTML5 Validasi memblokir submit form. | Pengiriman form dicegah oleh browser. | PASS |
| 4 | TC-04 | Autentikasi Admin | Login admin tanpa mencentang checkbox robot. | `Username: admin`, `Password: admin`, `verify_bot: false` | HTML5 Validasi memblokir submit form. | Pengiriman form dicegah oleh browser. | PASS |
| 5 | TC-05 | Landing Page | Membuka halaman utama dan memverifikasi seluruh modul. | Akses URL `/` | Judul mengandung kata "Sadita", seluruh seksi termuat. | Judul valid, seluruh 5 seksi utama ada. | PASS |
| 6 | TC-06 | Landing Page | Mengklik link kategori dekorasi pada menu cepat. | Klik anchor `#kategori-dekorasi` | Halaman melakukan auto-scroll ke seksi dekorasi. | URL bertambah hash `#kategori-dekorasi`. | PASS |
| 7 | TC-07 | Landing Page | Melakukan filter kategori galeri produk. | Klik tombol filter 'dekorasi' | Hanya produk kategori dekorasi yang tampil, kategori lain disembunyikan. | Element kategori lain mendapat class `masonry-item-hidden`. | PASS |
| 8 | TC-08 | Pemesanan | Membuka form order dengan data query parameter. | GET `/order?product=Papan+Premium&price=Rp+150.000` | Input Form terisi data produk secara otomatis. | Input field form terisi nama dan harga produk secara tepat. | PASS |
| 9 | TC-09 | Pemesanan | Mengirim pesanan mandiri secara lengkap. | `Nama: Ahsan`, `HP: 81234567890`, `Alamat: Padang`, `Tanggal: besok` | Dialihkan ke halaman invoice dengan status pembayaran unpaid. | Sukses redirect ke `/invoice/{code}` dengan status UNPAID. | PASS |
| 10 | TC-10 | Pemesanan | Mengirim form pesanan dengan field kosong. | Kosongkan field pengirim dan alamat. | Browser menahan submit dengan validasi form required. | Form tidak tersubmit, tetap di halaman order. | PASS |
| 11 | TC-11 | Pelacakan | Melacak pesanan dengan kode pesanan valid. | Input kode order baru hasil transaksi sukses. | Menampilkan box hasil pelacakan dengan status pembayaran UNPAID/PAID. | Status pesanan terdeteksi dan ditampilkan. | PASS |
| 12 | TC-12 | Pelacakan | Melacak pesanan menggunakan kode acak/tidak valid. | Input `SDT-NOTFOUND-999` | Menampilkan pesan error bahwa data tidak ditemukan. | Box status menampilkan "Tidak Ditemukan". | PASS |

---

## 5. DOKUMENTASI DAN PETUNJUK PENJALANAN AUTOMATED TESTING

Kode otomatis pengujian disimpan di folder [automated-testing](file:///d:/SaditaSystem/automated-testing).

### 5.1 Struktur Proyek Automated Testing
Proyek ini dibangun menggunakan **Apache Maven**:
```text
automated-testing/
|-- pom.xml
`-- src/
    `-- test/
        `-- java/
            `-- com/
                `-- sadita/
                    |-- BaseTest.java
                    |-- LoginTest.java
                    |-- HomeTest.java
                    |-- OrderTest.java
                    `-- TrackingTest.java
```

### 5.2 Cara Import dan Menjalankan Tes Melalui Eclipse IDE
1. Buka **Eclipse IDE** (Eclipse for Java Developers).
2. Pilih menu **File -> Import...**
3. Pilih **Maven -> Existing Maven Projects**, lalu klik **Next**.
4. Di bagian **Root Directory**, klik **Browse...** dan arahkan ke direktori: `d:\SaditaSystem\automated-testing`.
5. Pastikan file `pom.xml` terpilih di daftar project, lalu klik **Finish**.
6. Eclipse akan mengunduh dependencies (Selenium, JUnit 5) secara otomatis.
7. Untuk menjalankan seluruh pengujian:
   - Klik kanan pada folder project `automated-testing`.
   - Pilih **Run As -> JUnit Test**.
   - Atau klik kanan pada salah satu file uji (misalnya [LoginTest.java](file:///d:/SaditaSystem/automated-testing/src/test/java/com/sadita/LoginTest.java)) lalu pilih **Run As -> JUnit Test**.

### 5.3 Menjalankan Melalui Command Line (Terminal)
Gunakan Maven wrapper/cli di dalam folder `automated-testing`:
```bash
cd automated-testing
mvn test
```
*Catatan: Pastikan browser Google Chrome terpasang di komputer dan server lokal Laravel Anda berjalan di alamat `http://127.0.0.1:8000` (`php artisan serve`).*
