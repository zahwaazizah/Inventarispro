# Sistem Pendataan Inventaris Barang Menggunakan QR Code Berbasis Web

## 📋 Deskripsi Proyek

Sistem ini dikembangkan untuk membantu perusahaan dalam mengelola data inventaris secara terpusat, terstruktur, dan efisien. Proses pendataan inventaris yang sebelumnya masih dilakukan secara manual menggunakan buku catatan atau file spreadsheet kini dapat dilakukan secara digital melalui sistem berbasis web ini.

Sistem memanfaatkan teknologi **QR Code** sebagai identitas unik setiap barang, sehingga proses pendataan, pencarian informasi barang, pemantauan lokasi penyimpanan, stok, kondisi, serta riwayat peminjaman dan pengembalian dapat dilakukan secara lebih cepat dan akurat melalui satu dashboard terpusat. Sistem ini dapat diakses melalui browser menggunakan smartphone pribadi (konsep **Bring Your Own Device / BYOD**), tanpa perlu instalasi aplikasi tambahan.

**Tujuan Pengembangan:**
- Mempermudah proses pendataan dan pencarian barang inventaris
- Mencegah kesalahan pencatatan data inventaris
- Menyediakan laporan inventaris secara otomatis (PDF/Excel/CSV)
- Mendukung pencatatan peminjaman dan pengembalian barang secara terstruktur
- Memantau stok barang secara real-time

---

## ✨ Fitur Utama

### Admin
| Fitur | Deskripsi |
|-------|-----------|
| **Manajemen Barang** | Tambah, lihat, ubah, hapus data barang inventaris |
| **Manajemen Kategori** | Kelola kategori barang |
| **Manajemen Lokasi** | Kelola lokasi penyimpanan barang |
| **Manajemen Pengguna** | Kelola akun petugas |
| **Generate QR Code** | Buat QR Code unik untuk setiap barang |
| **Peminjaman Aktif** | Monitor barang yang sedang dipinjam |
| **Riwayat Transaksi** | Lihat riwayat peminjaman & pengembalian |
| **Laporan** | Cetak & unduh laporan (PDF/Excel/CSV) |

### Petugas
| Fitur | Deskripsi |
|-------|-----------|
| **Scan QR Code** | Pindai QR Code untuk melihat detail barang |
| **Pencarian Barang** | Cari barang berdasarkan nama atau kode |
| **Peminjaman Barang** | Catat transaksi peminjaman |
| **Pengembalian Barang** | Catat transaksi pengembalian |
| **Peminjaman Aktif** | Lihat barang yang sedang dipinjam |
| **Riwayat Transaksi** | Lihat riwayat peminjaman & pengembalian |

---

## 🛠️ Tech Stack

| Komponen | Teknologi |
|----------|-----------|
| **Backend** | PHP 8.2.4, Laravel 12.38.1 |
| **Database** | MySQL / MariaDB 10.4.28 |
| **Frontend** | Blade Template + Bootstrap 5 |
| **QR Code** | Library QR Code Generator |
| **PDF Export** | DomPDF |
| **Excel Export** | Laravel Excel |
| **Autentikasi** | Laravel Built-in Auth |

---

## 📦 Instalasi & Setup

### Prasyarat
- PHP 8.2.4 atau lebih tinggi
- Composer
- MySQL / MariaDB
- XAMPP (opsional, untuk lingkungan lokal)

### Langkah Instalasi

1. **Clone Repository**
   ```bash
   git clone https://github.com/username/inventaris-qr-code.git
   cd inventaris-qr-code
