<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SchoolController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\DailyMenuController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SppgController;
use App\Http\Controllers\AdminVendorController;

Route::middleware('auth')->group(function () {

    // DASHBOARD ADMIN
    Route::get('/admin/dashboard', fn() => view('admin.dashboardadmin'))->name('dashboard');

    // =========================
    // ADMIN: KELOLA AKUN SPPG
    // =========================
    Route::get('/admin/vendors', [AdminVendorController::class, 'index'])
        ->name('admin.vendors.index');
    Route::post('/admin/vendors', [AdminVendorController::class, 'store'])
        ->name('admin.vendors.store');
    Route::put('/admin/vendors/{vendor}', [AdminVendorController::class, 'update'])
        ->name('admin.vendors.update');
    Route::delete('/admin/vendors/{vendor}', [AdminVendorController::class, 'destroy'])
        ->name('admin.vendors.destroy');

    // -------------------------
    // SPPG (Vendor)
    // -------------------------
    Route::get('/sppg/riwayat', [SppgController::class, 'index'])->name('sppg.riwayat');
    Route::get('/sppg/ajukan', [SppgController::class, 'ajukanForm'])->name('ajukan.view');
    Route::post('/sppg/ajukan', [SppgController::class, 'storeMenu'])->name('ajukan');

    Route::get('/sppg/menu', [SppgController::class, 'dailyForm'])->name('sppg.input.menu.view');
    Route::post('/sppg/menu', [SppgController::class, 'storeDaily'])->name('sppg.input.menu');

    Route::get('/sppg/daily_detail/{id}', [DailyMenuController::class, 'sppgDetail'])
        ->name('sppg.daily.detail');

    Route::post('/sppg/comment-reply/{commentId}', [DailyMenuController::class, 'replyComment'])
        ->name('sppg.comment.reply');

    Route::post('/comment/{id}/reply', [DailyMenuController::class, 'storeReply'])
        ->name('daily.comment.reply');
});

// ----------------------------
// Public Routes
// ----------------------------
Route::get('/', fn() => view('public.dashboard'))->name('home');
Route::get('/tentang', fn() => view('public.about'))->name('about');

Route::get('/cari', [SchoolController::class, 'index'])->name('cari');

Route::get('/school/{id}', [SchoolController::class, 'show'])->name('schools.show');

// Menu tanpa tanggal
Route::get('/menu/{id}', [MenuController::class, 'show'])->name('menu.show');

// Daily menu dengan tanggal
Route::get('/daily-menu/{id}', [DailyMenuController::class, 'show'])->name('daily.show');
Route::post('/daily-menu/{id}/comment', [DailyMenuController::class, 'storeComment'])
    ->name('daily.comment.store');

// Public
Route::get('/daily-menu/{id}', [DailyMenuController::class, 'show'])->name('daily.show');
Route::post('/daily-menu/{id}/comment', [DailyMenuController::class, 'storeComment'])->name('daily.comment.store');

// ----------------------------
// Authentication
// ----------------------------
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'loginPage'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ----------------------------
// SPPG
// ----------------------------
Route::middleware('auth')->group(function () {

    Route::get('/admin/dashboard', fn() => view('admin.dashboardadmin'))->name('dashboard');

    Route::get('/sppg/riwayat', [SppgController::class, 'index'])->name('sppg.riwayat');
    Route::get('/sppg/ajukan', [SppgController::class, 'ajukanForm'])->name('ajukan.view');
    Route::post('/sppg/ajukan', [SppgController::class, 'storeMenu'])->name('ajukan');

    Route::get('/sppg/menu', [SppgController::class, 'dailyForm'])->name('sppg.input.menu.view');
    Route::post('/sppg/menu', [SppgController::class, 'storeDaily'])->name('sppg.input.menu');

    // Daily menu versi vendor/SPPG
    Route::get('/sppg/daily_detail/{id}', [DailyMenuController::class, 'sppgDetail'])
        ->name('sppg.daily.detail');

    // Balas komentar
    Route::post('/sppg/comment-reply/{commentId}', [DailyMenuController::class, 'replyComment'])
        ->name('sppg.comment.reply');

    Route::post('/comment/{id}/reply', [DailyMenuController::class, 'storeReply'])
        ->middleware('auth')
        ->name('daily.comment.reply');
});
