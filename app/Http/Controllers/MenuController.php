<?php

namespace App\Http\Controllers;

use App\Models\Menu;

class MenuController extends Controller
{
    public function show($id)
    {
        $menu = Menu::with(['items', 'nutrition'])->findOrFail($id);
        return view('public.menu_detail', compact('menu'));
    }
}
