<?php

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Http\Request;

// Diagnostic mode: open the site with ?__debug=1 to see the real PHP error instead of a blank HTTP 500.
$debug = isset($_GET['__debug']);
if ($debug) {
    ini_set('display_errors', '1');
    ini_set('display_startup_errors', '1');
    error_reporting(E_ALL);
    header('Content-Type: text/plain; charset=utf-8');
    register_shutdown_function(function () {
        $e = error_get_last();
        if ($e && in_array($e['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR], true)) {
            echo "\n[FATAL] {$e['message']}\n in {$e['file']}:{$e['line']}\n";
        }
    });
}

try {
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
    $isNewDb = !file_exists($sqlitePath) || filesize($sqlitePath) === 0;

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

    // 4. Register Composer autoloader
    require __DIR__ . '/../vendor/autoload.php';

    // 5. Bootstrap Laravel application once
    /** @var \Illuminate\Foundation\Application $app */
    $app = require __DIR__ . '/../bootstrap/app.php';

    // 6. Auto-run migrations & seeders if database is brand new in /tmp
    if ($isNewDb) {
        /** @var Kernel $kernel */
        $kernel = $app->make(Kernel::class);
        $kernel->call('migrate', ['--force' => true]);
        $kernel->call('db:seed', ['--force' => true]);
    }

    // 7. Handle the HTTP request
    $app->handleRequest(Request::capture());
} catch (\Throwable $e) {
    http_response_code(500);
    if ($debug) {
        echo get_class($e) . ': ' . $e->getMessage() . "\n in " . $e->getFile() . ':' . $e->getLine() . "\n\n" . $e->getTraceAsString();
    } else {
        error_log((string) $e);
        echo 'Server Error';
    }
}
