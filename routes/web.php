<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\DokuPaymentController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/order', [OrderController::class, 'create'])->name('order');
Route::get('/order/{order_id}/edit', [OrderController::class, 'edit'])->name('order.edit');
Route::post('/order', [OrderController::class, 'store'])->name('order.store');
Route::get('/invoice/{order_id}', [OrderController::class, 'show'])->name('invoice.show');
Route::get('/invoice/{order_id}/print', [OrderController::class, 'print'])->name('invoice.print');

Route::post('/invoice/{order_id}/promo', [OrderController::class, 'applyPromo'])->name('order.apply-promo');
Route::get('/api/track/{order_id}', [OrderController::class, 'track'])->name('order.track');
Route::post('/invoice/{order_id}/pay/doku', [DokuPaymentController::class, 'checkout'])->name('doku.checkout');
Route::post('/invoice/{order_id}/pay/doku/refresh', [DokuPaymentController::class, 'refreshStatus'])->name('doku.refresh');
Route::get('/payments/doku/return/{order_id}', [DokuPaymentController::class, 'handleReturn'])->name('doku.return');
Route::post('/payments/doku/notify', [DokuPaymentController::class, 'handleNotification'])->name('doku.notify');

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Forget Password Routes
Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');
Route::get('/reset-password/{token}', [AuthController::class, 'showResetPassword'])->name('password.reset');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');

Route::middleware(['auth', 'check.role:owner,admin'])->group(function () {
    Route::redirect('/admin-lite', '/admin', 301);
    Route::redirect('/admin-lite/{focus}', '/admin/{focus}', 301)
        ->where('focus', 'dashboard|kategori|produk|promo|pelanggan|ulasan|pesanan|pembayaran|aktivitas|users');
    Route::redirect('/admin-lite/{focus}/{mode}', '/admin/{focus}/{mode}', 301)
        ->where('focus', 'dashboard|kategori|produk|promo|pelanggan|ulasan|pesanan|pembayaran|aktivitas|users')
        ->where('mode', 'overview|manage|create');
    Route::redirect('/admin-lite/{focus}/{record}/edit', '/admin/{focus}/{record}/edit', 301)
        ->where('focus', 'kategori|produk|promo|pelanggan|ulasan|pesanan|pembayaran|users')
        ->whereNumber('record');

    Route::get('/admin/{focus?}/{mode?}', [AdminController::class, 'index'])
        ->where('focus', 'dashboard|kategori|produk|promo|pelanggan|ulasan|pesanan|pembayaran|aktivitas|users')
        ->where('mode', 'overview|manage|create')
        ->name('admin.index');
    Route::get('/admin/search', [AdminController::class, 'showSearchResults'])
        ->name('admin.search');
    Route::get('/admin/search/global', [AdminController::class, 'globalSearch'])
        ->name('admin.search.global');
    Route::post('/admin/notifications/seen', [AdminController::class, 'markNotificationsSeen'])
        ->name('admin.notifications.seen');
    Route::get('/admin/{focus}/{record}/edit', [AdminController::class, 'edit'])
        ->where('focus', 'kategori|produk|promo|pelanggan|ulasan|pesanan|pembayaran|users')
        ->name('admin.edit');
    Route::post('/admin/{focus}/store', [AdminController::class, 'store'])
        ->where('focus', 'kategori|produk|promo|pelanggan|ulasan|pesanan|pembayaran|users')
        ->name('admin.store');
    Route::put('/admin/{focus}/{record}', [AdminController::class, 'update'])
        ->where('focus', 'kategori|produk|promo|pelanggan|ulasan|pesanan|pembayaran|users')
        ->name('admin.update');
    Route::delete('/admin/{focus}/{record}', [AdminController::class, 'destroy'])
        ->where('focus', 'kategori|produk|promo|pelanggan|ulasan|pesanan|pembayaran|users')
        ->name('admin.destroy');
});
