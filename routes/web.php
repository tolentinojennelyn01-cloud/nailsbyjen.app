<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;

// Set the booking form as the homepage (http://127.0.0.1:8000)
Route::get('/', [OrderController::class, 'create'])->name('booking.create');

// Public booking routes
Route::get('/book', [OrderController::class, 'create']);
Route::post('/book', [OrderController::class, 'store'])->name('booking.store');
Route::get('/book/confirmation/{order}', [OrderController::class, 'confirmation'])->name('booking.confirmation');

// Admin area (without login restriction for testing)
// Re-add ->middleware(['auth']) later when authentication is set up
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::patch('/orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');
    Route::patch('/orders/{order}/home-service-fee', [OrderController::class, 'updateHomeServiceFee'])->name('orders.updateHomeServiceFee');
    Route::delete('/orders/{order}', [OrderController::class, 'destroy'])->name('orders.destroy');
});