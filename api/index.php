<?php

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

putenv('VIEW_COMPILED_PATH=/tmp/storage/framework/views');
$_ENV['VIEW_COMPILED_PATH'] = '/tmp/storage/framework/views';
$_SERVER['VIEW_COMPILED_PATH'] = '/tmp/storage/framework/views';

// Auto-run migrations & seeders if database is brand new in /tmp
if ($isNewDb) {
    try {
        require __DIR__ . '/../vendor/autoload.php';
        $app = require_once __DIR__ . '/../bootstrap/app.php';
        $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
        $kernel->call('migrate', ['--force' => true]);
        $kernel->call('db:seed', ['--force' => true]);
    } catch (\Throwable $e) {
        // Fallback silently if migrations already seeded
    }
}

// 4. Require Laravel public index
require __DIR__ . '/../public/index.php';
