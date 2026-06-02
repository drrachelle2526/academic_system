<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

$email = 'teacher@example.com';
$new = 'password';

$u = User::where('email', $email)->first();
if ($u) {
    $u->password = Hash::make($new);
    $u->save();
    echo "updated\n";
} else {
    echo "not found\n";
}
