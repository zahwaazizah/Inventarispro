<?php
// Create directory structure for Inventaris Pro
$dirs = [
    'app/Http/Middleware',
    'resources/views/auth',
    'resources/views/dashboard',
    'resources/views/inventaris',
    'resources/views/kategori',
    'resources/views/lokasi',
    'resources/views/qr',
    'resources/views/transaksi',
    'resources/views/riwayat',
    'resources/views/laporan',
    'resources/views/users',
    'resources/views/layouts',
];

foreach ($dirs as $dir) {
    if (!is_dir($dir)) {
        @mkdir($dir, 0755, true);
    }
}

echo "Direktori berhasil dibuat!";
?>
