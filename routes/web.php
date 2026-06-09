<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/order', function () {
    return view('pages.home.order');
})->name('order');
Route::post('/order', [OrderController::class, 'store'])->name('order.store');
Route::post('/api/validate-promo', [OrderController::class, 'validatePromo'])->name('promo.validate');
Route::get('/invoice/{order_id}', [OrderController::class, 'show'])->name('invoice.show');
Route::get('/api/track/{order_id}', [OrderController::class, 'track'])->name('order.track');

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
