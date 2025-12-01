<?php

namespace App\Http\Controllers;

use App\Models\School;
use App\Models\DailyMenu;

class SchoolController extends Controller
{
    public function show($id)
    {
        $school = School::findOrFail($id);

        $dailyMenus = DailyMenu::with('menu')
            ->whereHas('menu', function ($q) use ($school) {
                $q->where('school_id', $school->id);
            })
            ->orderBy('date', 'desc')
            ->get();

        return view('public.school_detail', compact('school', 'dailyMenus'));
    }
}
