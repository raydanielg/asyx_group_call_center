<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Hash;
$u = App\Models\User::where('email', 'admin@ayscallcenter.com')->first();
if ($u) {
    echo "Found: " . $u->email . "\n";
    echo "Hash check: " . (Hash::check('password', $u->password) ? 'YES' : 'NO') . "\n";
    echo "Role: " . $u->role . "\n";
} else {
    echo "NOT FOUND\n";
}
