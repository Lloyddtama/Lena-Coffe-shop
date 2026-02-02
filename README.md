# 🛒 Lena Coffee shop - Modern Point of Sales (POS) System

[![PHP Version](https://img.shields.io/badge/php-%5E8.0-777bb4.svg?style=flat-square&logo=php)](https://www.php.net/)
[![Bootstrap](https://img.shields.io/badge/bootstrap-%5E5.3-563d7c.svg?style=flat-square&logo=bootstrap)](https://getbootstrap.com/)
[![License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE)

**Kasir Kita** adalah aplikasi kasir berbasis web yang dirancang untuk UMKM dengan antarmuka modern, responsif, dan interaktif. Aplikasi ini mengelola stok secara real-time, manajemen user, hingga laporan penjualan otomatis.

## ✨ Fitur Utama
- 📊 **Dashboard Executive**: Ringkasan total produk, stok, dan kasir dalam satu tampilan.
- ⚡ **Transaksi Instan**: Sistem keranjang belanja interaktif dengan notifikasi SweetAlert2.
- 📦 **Manajemen Inventori**: Kelola stok produk secara otomatis saat transaksi terjadi.
- 👥 **Multi-User Role**: Akses berbeda untuk Admin (Full Akses) dan Kasir (Hanya Transaksi).
- 📅 **Laporan Bulanan**: Filter laporan penjualan berdasarkan bulan & tahun beserta total omzet.
- 🧾 **Cetak Struk**: Fitur cetak struk otomatis setelah transaksi berhasil.

## 🛠️ Teknologi yang Digunakan
- **Bahasa:** PHP 8.x
- **Database:** MySQL
- **Frontend:** Bootstrap 5, FontAwesome 6, AOS (Animate on Scroll)
- **Library Tambahan:** SweetAlert2 (Pop-up modern)

## 📸 Tampilan Aplikasi
*(Opsional: Masukkan link gambar screenshot kamu di sini)*

## 🚀 Cara Instalasi
1. Clone repository ini:
   ```bash
   git clone [https://github.com/username-kamu/kasir-kita.git](https://github.com/username-kamu/kasir-kita.git)
2. Import database database/db_kasir_kita.sql ke MySQL (phpMyAdmin).
3. Sesuaikan konfigurasi database di koneksi.php.
4. Jalankan pada server lokal (XAMPP/Laragon).

Login default:
Admin: admin | pass: admin123
Kasir: kasir1 | pass: kasir123

### 2. Rekomendasi Hosting Gratis & Caranya
Ada dua opsi terbaik untuk PHP saat ini:

#### A. InfinityFree (Paling Populer & Stabil)
Ini adalah hosting gratis tanpa iklan yang paling cocok untuk pemula.
* **Kelebihan:** Gratis selamanya, ada MySQL, File Manager online, support PHP terbaru.
* **Cara Upload:**
    1. Daftar di [infinityfree.com](https://www.infinityfree.com/).
    2. Buat "Accounts" baru dan pilih subdomain gratis (misal: `kasirkita.infy.uk`).
    3. Di **Control Panel**, cari menu **MySQL Databases** dan buat database baru.
    4. Buka **phpMyAdmin** di hosting tersebut, lalu import file `.sql` kamu.
    5. Masuk ke **Online File Manager**, buka folder `htdocs`, lalu upload semua file PHP kamu ke sana.
    6. **Penting:** Edit `koneksi.php` di hosting, sesuaikan `host`, `user`, `pass`, dan `db_name` dengan data dari Control Panel InfinityFree.

#### B. 000webhost (Alternatif)
Milik Hostinger, sangat mudah digunakan tapi ada batasan waktu tidur (*sleep time*) 1 jam setiap hari.
* **Cara Upload:** Hampir sama dengan InfinityFree, kamu cukup upload file melalui menu **Website Builder** atau **File Manager**.


### 3. Tips Tambahan untuk Portofolio
* **Gunakan Git:** Jangan cuma upload ke hosting, upload juga kodenya ke **GitHub**. Link GitHub ini yang kamu taruh di CV.
* **Live Preview:** Di bio Instagram atau profil LinkedIn, tulis: *"Cek aplikasi POS buatan saya di: [link-hosting-kamu]"*.
* **Screenshot:** Gunakan ekstensi browser seperti *GoFullPage* untuk menangkap seluruh halaman dari atas sampai bawah untuk dipajang sebagai gambar portofolio.

**Gas!** Proyek ini sudah sangat layak untuk dipamerkan. Apakah ada bagian kode lain yang masih mengganjal sebelum kamu "bungkus" proyek ini?
