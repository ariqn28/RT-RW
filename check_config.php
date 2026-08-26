<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo 'DB_DEFAULT: ' . config('database.default') . PHP_EOL;
echo 'DB_SQLITE_PATH: ' . config('database.connections.sqlite.database') . PHP_EOL;
echo 'DB_EXISTS: ' . (file_exists(config('database.connections.sqlite.database')) ? 'YES' : 'NO') . PHP_EOL;
echo 'SESSION_SECURE: ' . (config('session.secure') ? 'true' : 'false') . PHP_EOL;
echo 'SESSION_LIFETIME: ' . config('session.lifetime') . PHP_EOL;
echo 'APP_URL: ' . config('app.url') . PHP_EOL;

