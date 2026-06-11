<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$request = Illuminate\Http\Request::capture();
$response = $kernel->handle($request);

$router = $app->get('router');
$routes = $router->getRoutes();

echo "<h3>Route yang mengandung 'inventaris'</h3><ul>";
foreach ($routes as $route) {
    $uri = $route->uri();
    if (strpos($uri, 'inventaris') !== false) {
        $name = $route->getName() ?: 'tidak bernama';
        echo "<li><strong>$uri</strong> → $name</li>";
    }
}
echo "</ul>";

// Cek apakah route 'inventaris.create' ada
$hasCreate = false;
foreach ($routes as $route) {
    if ($route->getName() == 'inventaris.create') {
        $hasCreate = true;
        break;
    }
}
echo "<p>Route inventaris.create: " . ($hasCreate ? "✅ TERDAFTAR" : "❌ TIDAK TERDAFTAR") . "</p>";

$kernel->terminate($request, $response);
?>