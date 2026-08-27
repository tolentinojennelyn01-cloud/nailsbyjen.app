<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\AdminAuthController;

// Set the booking form as the homepage (http://127.0.0.1:8000)
Route::get('/', [OrderController::class, 'create'])->name('booking.create');

// Public booking routes
Route::get('/book', [OrderController::class, 'create']);
Route::post('/book', [OrderController::class, 'store'])->name('booking.store');
Route::get('/book/confirmation/{order}', [OrderController::class, 'confirmation'])->name('booking.confirmation');

// Admin login (public, but rate-limited against password guessing)
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AdminAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('login.attempt')->middleware('throttle:5,1');
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');
});

// Admin area (password-protected)
Route::prefix('admin')->name('admin.')->middleware('admin.auth')->group(function () {
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::patch('/orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');
    Route::delete('/orders/{order}', [OrderController::class, 'destroy'])->name('orders.destroy');
});