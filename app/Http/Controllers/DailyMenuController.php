<?php

namespace App\Http\Controllers;

use App\Models\DailyMenu;
use App\Models\Comment;
use Illuminate\Http\Request;

class DailyMenuController extends Controller
{
    // Public detail
    public function show($id)
    {
        $daily = DailyMenu::with([
            'menu.vendor.schools',
            'menu.items',
            'menu.nutrition',
            'school',
            'comments'
        ])->findOrFail($id);

        return view('public.menu_detail', [
            'daily' => $daily,
            'menu' => $daily->menu
        ]);
    }

    // Public comment
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

    // View untuk SPPG (vendor)
    public function sppgDetail($id)
    {
        $daily = DailyMenu::with([
            'menu.items',
            'menu.nutrition',
            'comments'
        ])->findOrFail($id);

        return view('sppg.daily_detail', compact('daily'));
    }

    // Vendor reply comment
    public function replyComment(Request $request, $commentId)
    {
        $request->validate([
            'reply' => 'required|string|max:255'
        ]);

        $comment = Comment::findOrFail($commentId);
        $comment->update([
            'reply' => $request->reply
        ]);

        return back()->with('success', 'Balasan berhasil dikirim');
    }

    public function storeReply(Request $request, $commentId)
    {
        $request->validate([
            'user_name' => 'required',
            'body' => 'required'
        ]);

        $comment = Comment::findOrFail($commentId);

        Comment::create([
            'daily_menu_id' => $comment->daily_menu_id,
            'parent_id' => $commentId,
            'user_name' => $request->user_name,
            'body' => $request->body
        ]);

        return back()->with('success', 'Balasan berhasil ditambahkan!');
    }
}
