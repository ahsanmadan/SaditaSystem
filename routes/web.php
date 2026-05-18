<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/order', function () {
    return view('pages.home.order');
})->name('order');
Route::post('/order', [App\Http\Controllers\OrderController::class, 'store'])->name('order.store');
Route::get('/invoice/{order_id}', [App\Http\Controllers\OrderController::class, 'show'])->name('invoice.show');
Route::get('/api/track/{order_id}', [App\Http\Controllers\OrderController::class, 'track'])->name('order.track');

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
