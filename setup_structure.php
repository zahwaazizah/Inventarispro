#!/usr/bin/env php
<?php
/**
 * Script untuk membuat struktur folder Inventaris Pro
 */

$basePath = __DIR__;

$directories = [
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

echo "📁 Creating directory structure for Inventaris Pro...\n\n";

foreach ($directories as $dir) {
    $path = $basePath . DIRECTORY_SEPARATOR . $dir;
    if (!is_dir($path)) {
        mkdir($path, 0755, true);
        echo "✓ Created: {$dir}\n";
    } else {
        echo "→ Exists: {$dir}\n";
    }
}

echo "\n✓ Directory structure created successfully!\n";
