<?php

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Http\Request;

// 1. Prepare writable storage directories in /tmp for Vercel Serverless environment
$tmpDirs = [
    '/tmp/storage/framework/views',
    '/tmp/storage/framework/sessions',
    '/tmp/storage/framework/cache/data',
    '/tmp/storage/app/public',
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
if (!file_exists($sqlitePath)) {
    @touch($sqlitePath);
}

// 3. Set environment fallback variables for serverless compatibility
$setEnv = function (string $key, string $value) {
    putenv("{$key}={$value}");
    $_ENV[$key] = $value;
    $_SERVER[$key] = $value;
};

if (!getenv('DB_DATABASE') && (!getenv('DB_CONNECTION') || getenv('DB_CONNECTION') === 'sqlite')) {
    $setEnv('DB_DATABASE', $sqlitePath);
}

$setEnv('APP_STORAGE_PATH', '/tmp/storage');
$setEnv('VIEW_COMPILED_PATH', '/tmp/storage/framework/views');

// Keep Laravel's bootstrap caches writable too
$setEnv('APP_SERVICES_CACHE', '/tmp/bootstrap/cache/services.php');
$setEnv('APP_PACKAGES_CACHE', '/tmp/bootstrap/cache/packages.php');
$setEnv('APP_CONFIG_CACHE', '/tmp/bootstrap/cache/config.php');
$setEnv('APP_ROUTES_CACHE', '/tmp/bootstrap/cache/routes-v7.php');
$setEnv('APP_EVENTS_CACHE', '/tmp/bootstrap/cache/events.php');

// Buffer output to prevent premature header flushing during bootstrap/seeding
ob_start();

try {
    // 4. Register Composer autoloader
    require __DIR__ . '/../vendor/autoload.php';

    // 5. Bootstrap Laravel application once
    /** @var \Illuminate\Foundation\Application $app */
    $app = require __DIR__ . '/../bootstrap/app.php';

    /** @var Kernel $kernel */
    $kernel = $app->make(Kernel::class);
    $kernel->bootstrap();

    // 6. Auto-run migrations & seeders if SQLite database has no products
    if (config('database.default') === 'sqlite') {
        $needsSeed = false;
        try {
            $needsSeed = !\Illuminate\Support\Facades\Schema::hasTable('products')
                || \Illuminate\Support\Facades\DB::table('products')->count() === 0;
        } catch (\Throwable $e) {
            $needsSeed = true;
        }

        if ($needsSeed) {
            @set_time_limit(120);
            $kernel->call('migrate', ['--force' => true]);
            \Illuminate\Support\Facades\DB::transaction(function () use ($kernel) {
                $kernel->call('db:seed', ['--force' => true]);
            });
        }
    }

    // Clear any output produced during bootstrapping/seeding before sending HTTP response
    if (ob_get_level()) {
        ob_end_clean();
    }

    // 7. Handle the HTTP request
    $app->handleRequest(Request::capture());

} catch (\Throwable $e) {
    if (ob_get_level()) {
        ob_end_clean();
    }
    http_response_code(500);
    error_log((string) $e);

    if (isset($_GET['__debug'])) {
        header('Content-Type: text/plain; charset=utf-8');
        echo get_class($e) . ': ' . $e->getMessage() . "\n in " . $e->getFile() . ':' . $e->getLine() . "\n\n" . $e->getTraceAsString();
    } else {
        echo 'Server Error';
    }
}
