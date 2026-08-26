<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$users = Illuminate\Support\Facades\DB::table('users')->select('email', 'name', 'role')->get();
foreach ($users as $u) {
    echo $u->email . ' | ' . $u->name . ' | ' . $u->role . "\n";
}

