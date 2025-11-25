<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Models\School;
use App\Models\Menu;
use App\Models\MenuItem;
use App\Models\NutritionValue;
use App\Models\Comment;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return view('public.dashboard');
});

Route::get('/menu', function () {
    $schools = School::all();
    return view('public.menu', compact('schools'));
});

Route::get('/school/{id}', function ($id) {
    $school = School::findOrFail($id);
    $menus = Menu::where('school_id', $id)->get();
    return view('public.school_detail', compact('school', 'menus'));
});

Route::get('/menu/{id}', function ($id) {
    $menu = Menu::with(['items', 'nutrition'])->findOrFail($id);

    $comments = Comment::where('menu_id', $id)
                        ->orderBy('created_at', 'desc')
                        ->get();

    return view('public.menu_detail', compact('menu', 'comments'));
});

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
});

Route::get('/login', [AuthController::class, 'loginPage'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/logout', [AuthController::class, 'logout']);