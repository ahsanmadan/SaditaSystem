<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DokuPaymentController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/order', [OrderController::class, 'create'])->name('order');
Route::get('/order/{order_id}/edit', [OrderController::class, 'edit'])->name('order.edit');
Route::post('/order', [OrderController::class, 'store'])->name('order.store');
Route::get('/invoice/{order_id}', [OrderController::class, 'show'])->name('invoice.show');
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
