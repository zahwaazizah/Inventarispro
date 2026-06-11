<?php
// Script untuk membuat folder structure
// Jalankan dengan: php create-folders.php

$base = __DIR__;

$folders = [
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

echo "Creating folder structure...\n\n";

foreach ($folders as $folder) {
    $path = $base . DIRECTORY_SEPARATOR . $folder;
    
    if (!is_dir($path)) {
        if (mkdir($path, 0755, true)) {
            echo "✓ Created: $folder\n";
        } else {
            echo "✗ Failed to create: $folder\n";
        }
    } else {
        echo "→ Already exists: $folder\n";
    }
}

echo "\n✓ Folder structure created successfully!\n";
echo "\nNext step: Run 'php artisan project:structure' or use the setup guide in SETUP_STRUCTURE.md\n";

