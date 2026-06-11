# Panduan Membuat Struktur Folder InventarisPro

Jalankan perintah-perintah berikut di terminal untuk membuat struktur folder dan file yang lengkap:

## 1. Buat Folder Structure

```bash
# Controllers sudah tersedia di: app/Http/Controllers/
# - AuthController.php ✓
# - DashboardController.php ✓
# - InventarisController.php ✓
# - KategoriController.php ✓
# - LokasiController.php ✓
# - QrCodeController.php ✓
# - TransaksiController.php ✓
# - RiwayatController.php ✓
# - LaporanController.php ✓
# - UserController.php ✓

# Buat Middleware folder
mkdir app\Http\Middleware

# Buat Views folder structure
mkdir resources\views\auth
mkdir resources\views\dashboard
mkdir resources\views\inventaris
mkdir resources\views\kategori
mkdir resources\views\lokasi
mkdir resources\views\qr
mkdir resources\views\transaksi
mkdir resources\views\riwayat
mkdir resources\views\laporan
mkdir resources\views\users
mkdir resources\views\layouts
```

## 2. Buat Models (jika belum ada)

```bash
php artisan make:model Barang -m
php artisan make:model Kategori -m
php artisan make:model Lokasi -m
php artisan make:model Riwayat -m
```

## 3. Update User Model

Pastikan User model memiliki field `role`:

```bash
php artisan make:migration add_role_to_users_table
```

Di migration file, tambahkan:
```php
$table->enum('role', ['admin', 'petugas'])->default('petugas');
```

Kemudian jalankan migration:
```bash
php artisan migrate
```

## 4. Struktur Lengkap yang Sudah Dibuat:

### Controllers (✓ Selesai):
```
app/Http/Controllers/
├── AuthController.php
├── DashboardController.php
├── InventarisController.php
├── KategoriController.php
├── LokasiController.php
├── QrCodeController.php
├── TransaksiController.php
├── RiwayatController.php
├── LaporanController.php
└── UserController.php
```

### Models (✓ Selesai):
```
app/Models/
├── User.php (sudah ada)
├── Barang.php
├── Kategori.php
├── Lokasi.php
└── Riwayat.php
```

### Middleware (Perlu dibuat):
```
app/Http/Middleware/
└── RoleMiddleware.php
```

### Views (Perlu dibuat):
```
resources/views/
├── auth/
│   ├── login.blade.php
│   └── register.blade.php
├── dashboard/
│   ├── admin.blade.php
│   └── petugas.blade.php
├── inventaris/
│   ├── index.blade.php
│   ├── create.blade.php
│   ├── edit.blade.php
│   └── show.blade.php
├── kategori/
│   ├── index.blade.php
│   ├── create.blade.php
│   └── edit.blade.php
├── lokasi/
│   ├── index.blade.php
│   ├── create.blade.php
│   └── edit.blade.php
├── qr/
│   ├── scan.blade.php
│   └── index.blade.php
├── transaksi/
│   ├── index.blade.php
│   └── create.blade.php
├── riwayat/
│   └── index.blade.php
├── laporan/
│   └── index.blade.php
├── users/
│   ├── index.blade.php
│   ├── create.blade.php
│   └── edit.blade.php
├── layouts/
│   └── app.blade.php
└── welcome.blade.php
```

Semua file controller dan model sudah siap. Anda tinggal membuat folder structure dan menambahkan view files!
