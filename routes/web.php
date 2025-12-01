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

Route::get('/', function () {
    return view('public.dashboard');
})->name('home');

Route::get('/tentang', function () {
    return view('public.about');
})->name('about');

Route::get('/cari', function () {
    $schools = \App\Models\School::all();
    return view('public.schools', compact('schools'));
})->name('cari');

Route::get('/school/{id}', [SchoolController::class, 'show'])->name('schools.show');
Route::get('/menu/{id}',   [MenuController::class, 'show'])->name('menu.show');
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
// Dashboard
// ----------------------------

Route::middleware('auth')->group(function () {

    Route::get('/admin/dashboard', function () {
        return view('admin.dashboardadmin');
    })->name('dashboard');

    // SPPG Dashboard — ONLY THIS ONE!
    Route::get('/sppg/riwayat', [SppgController::class, 'index'])
        ->name('sppg.riwayat');
    
    Route::get('/sppg/ajukan', [SppgController::class, 'ajukanForm'])
        ->name('ajukan.view');
    
    Route::get('/sppg/menu', [SppgController::class, 'dailyForm'])
        ->name('sppg.input.menu.view');

    Route::post('/sppg/ajukan', [SppgController::class, 'storeMenu'])
        ->name('ajukan');

    Route::post('/sppg/menu', [SppgController::class, 'storeDaily'])
        ->name('sppg.input.menu');
});
