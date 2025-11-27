<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Models\School;
use App\Models\Menu;
use App\Models\MenuItem;
use App\Models\NutritionValue;
use App\Models\Comment;
use App\Http\Controllers\AuthController;

// Public Routes
Route::get('/', function () {
    return view('public.dashboard');
})->name('home');

Route::get('/tentang', function () {
    return view('public.about');
})->name('about');

Route::get('/cari', function () {
    $schools = School::all();
    return view('public.schools', compact('schools'));
})->name('cari');

Route::get('/school/{id}', function ($id) {
    $school = School::findOrFail($id);
    $menus = Menu::where('school_id', $id)->get();
    return view('public.school_detail', compact('school', 'menus'));
})->name('schools.show');

Route::get('/menu/{id}', function ($id) {
    $menu = Menu::with(['items', 'nutrition'])->findOrFail($id);
    $comments = Comment::where('menu_id', $id)
                      ->orderBy('created_at', 'desc')
                      ->get();
    return view('public.menu_detail', compact('menu', 'comments'));
})->name('menu.show');

Route::post('/menu/{id}/comment', function (Request $request, $id) {
    $request->validate([
        'name' => 'required|string|max:50',
        'body' => 'required|string|max:255',
    ]);

    Comment::create([
        'menu_id' => $id,
        'user_name' => $request->name,
        'body' => $request->body,
    ]);

    return back()->with('success', 'Komentar berhasil ditambahkan!');
})->name('comment.store');

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'loginPage'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Authenticated Dashboard Routes
Route::middleware('auth')->group(function () {
    Route::get('/admin/dashboard', function () {
        return view('admin.dashboardadmin');
    })->name('dashboard');

    Route::get('/sppg/dashboard', function () {
        return view('sppg.dashboardsppg');
    })->name('sppg.dashboard');
});