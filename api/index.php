<?php

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Http\Request;

// 1. Prepare writable storage directories in /tmp for Vercel Serverless environment
$tmpDirs = [
    '/tmp/storage/framework/views',
    '/tmp/storage/framework/sessions',
    '/tmp/storage/framework/cache',
    '/tmp/storage/logs',
    '/tmp/bootstrap/cache',
];

foreach ($tmpDirs as $dir) {
    if (!file_exists($dir)) {
        @mkdir($dir, 0755, true);
    }
}

// 2. Setup SQLite database file in /tmp if sqlite is used
$sqlitePath = '/tmp/database.sqlite';
$isNewDb = !file_exists($sqlitePath) || filesize($sqlitePath) === 0;

if (!file_exists($sqlitePath)) {
    @touch($sqlitePath);
}

// 3. Set environment fallback variables for serverless compatibility
if (!getenv('DB_DATABASE') && (!getenv('DB_CONNECTION') || getenv('DB_CONNECTION') === 'sqlite')) {
    putenv("DB_DATABASE={$sqlitePath}");
    $_ENV['DB_DATABASE'] = $sqlitePath;
    $_SERVER['DB_DATABASE'] = $sqlitePath;
}

putenv('APP_STORAGE_PATH=/tmp/storage');
$_ENV['APP_STORAGE_PATH'] = '/tmp/storage';
$_SERVER['APP_STORAGE_PATH'] = '/tmp/storage';

putenv('VIEW_COMPILED_PATH=/tmp/storage/framework/views');
$_ENV['VIEW_COMPILED_PATH'] = '/tmp/storage/framework/views';
$_SERVER['VIEW_COMPILED_PATH'] = '/tmp/storage/framework/views';

// 4. Register Composer autoloader
require __DIR__ . '/../vendor/autoload.php';

// 5. Bootstrap Laravel application once
/** @var \Illuminate\Foundation\Application $app */
$app = require __DIR__ . '/../bootstrap/app.php';

// 6. Auto-run migrations & seeders if database is brand new in /tmp
if ($isNewDb) {
    try {
        /** @var Kernel $kernel */
        $kernel = $app->make(Kernel::class);
        $kernel->call('migrate', ['--force' => true]);
        $kernel->call('db:seed', ['--force' => true]);
    } catch (\Throwable $e) {
        // Fallback silently if migrations fail
    }
}

// 7. Handle the HTTP request
$app->handleRequest(Request::capture());
