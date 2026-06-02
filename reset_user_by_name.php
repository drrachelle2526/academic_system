<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

$name = 'Elina';
$new = 'password';

$u = User::where('name', $name)->where('role', 'teacher')->first();
if (!$u) {
    // try email contains
    $u = User::where('email', 'like', "%{$name}%")->where('role', 'teacher')->first();
}

if ($u) {
    $u->password = Hash::make($new);
    $u->save();
    echo "updated: {$u->email} ({$u->name})\n";
} else {
    echo "no teacher user found with name or email containing '{$name}'\n";
}
