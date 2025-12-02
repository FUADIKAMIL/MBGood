<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SchoolController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\DailyMenuController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SppgController;

// ----------------------------
// Public Routes
// ----------------------------
Route::get('/', fn() => view('public.dashboard'))->name('home');
Route::get('/tentang', fn() => view('public.about'))->name('about');

Route::get('/cari', function () {
    $schools = \App\Models\School::all();
    return view('public.schools', compact('schools'));
})->name('cari');

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
