<?php

namespace App\Http\Controllers;

use App\Models\DailyMenu;
use App\Models\Comment;
use Illuminate\Http\Request;

class DailyMenuController extends Controller
{
    public function show($id)
    {
        $daily = DailyMenu::with(['menu.items', 'menu.nutrition', 'comments'])
            ->findOrFail($id);

        return view('public.daily_detail', compact('daily'));
    }

    public function storeComment(Request $request, $id)
    {
        $request->validate([
            'user_name' => 'required|string|max:50',
            'body' => 'required|string|max:255',
        ]);

        Comment::create([
            'daily_menu_id' => $id,
            'user_name' => $request->user_name,
            'body' => $request->body,
        ]);

        return back()->with('success', 'Komentar berhasil ditambahkan!');
    }
}
