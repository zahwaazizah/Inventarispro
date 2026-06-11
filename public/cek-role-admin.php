<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$request = Illuminate\Http\Request::capture();
$response = $kernel->handle($request);

use Illuminate\Support\Facades\Auth;
use App\Models\User;

if (Auth::check()) {
    $user = Auth::user();
    echo "ID: " . $user->id . "<br>";
    echo "Name: " . $user->name . "<br>";
    echo "Email: " . $user->email . "<br>";
    echo "Role: " . $user->role . "<br>";
    echo "Role is admin? " . ($user->role == 'admin' ? 'YES' : 'NO');
} else {
    echo "Not logged in. Please login first.";
}
$kernel->terminate($request, $response);
?>