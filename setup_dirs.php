<?php
// Bootstrap file to setup directory structure
// Run: php setup_dirs.php

define('BASE_PATH', __DIR__);

$dirs = [
    'app/Http/Middleware' => [],
    'resources/views/auth' => [
        'login.blade.php',
        'register.blade.php'
    ],
    'resources/views/dashboard' => [
        'admin.blade.php',
        'petugas.blade.php'
    ],
    'resources/views/inventaris' => [
        'index.blade.php',
        'create.blade.php',
        'edit.blade.php',
        'show.blade.php'
    ],
    'resources/views/kategori' => [
        'index.blade.php'
    ],
    'resources/views/lokasi' => [
        'index.blade.php'
    ],
    'resources/views/qr' => [
        'scan.blade.php',
        'index.blade.php'
    ],
    'resources/views/transaksi' => [
        'index.blade.php',
        'create.blade.php'
    ],
    'resources/views/riwayat' => [
        'index.blade.php'
    ],
    'resources/views/laporan' => [
        'index.blade.php'
    ],
    'resources/views/users' => [
        'index.blade.php'
    ],
    'resources/views/layouts' => [
        'app.blade.php'
    ]
];

foreach ($dirs as $dir => $files) {
    $path = BASE_PATH . '/' . $dir;
    @mkdir($path, 0755, true);
    
    // Create .gitkeep to ensure folder is tracked
    if (!file_exists($path . '/.gitkeep') && empty($files)) {
        file_put_contents($path . '/.gitkeep', '');
    }
}

echo "✓ Directory structure setup complete!\n";
echo "  - Run individual make:controller commands if needed\n";
echo "  - All view directories have been created\n";
