<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

$app = Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Vercel terminates TLS at its edge proxy; trust it so asset()/url() generate https:// links.
        $middleware->trustProxies(at: '*');

        // Redirect unauthenticated guests to login with informative flash message
        $middleware->redirectGuestsTo(function ($request) {
            session()->flash('warning', 'Silakan masuk ke akun Anda atau daftar akun baru terlebih dahulu untuk melanjutkan belanja.');
            return route('login');
        });

        // Exclude payment gateway webhook and Google callback from CSRF validation
        $middleware->validateCsrfTokens(except: [
            'api/midtrans/notification',
            'auth/google/callback',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();

// Dynamic storage path for Vercel serverless environment
if (isset($_ENV['VERCEL']) || isset($_SERVER['VERCEL']) || getenv('APP_STORAGE_PATH')) {
    $app->useStoragePath('/tmp/storage');
}

return $app;
