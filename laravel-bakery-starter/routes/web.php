<?php

use App\Http\Controllers\SiteController;
use Illuminate\Support\Facades\Route;

Route::get('/', [SiteController::class, 'home'])->name('home');
Route::get('/shop/{slug}', [SiteController::class, 'category'])->name('category');
Route::get('/reservations', [SiteController::class, 'reservations'])->name('reservations');
Route::get('/contact', [SiteController::class, 'contact'])->name('contact');
Route::post('/contact', [SiteController::class, 'sendContact'])->name('contact.send');
Route::get('/track-order', [SiteController::class, 'track'])->name('track');
