<?php

namespace App\Http\Controllers;

use App\Models\School;
use App\Models\DailyMenu;
use Illuminate\Http\Request;

class SchoolController extends Controller
{
   public function index(Request $request)
    {
        $search = $request->get('q');

        $schools = School::when($search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                      ->orWhere('address', 'like', "%{$search}%");
            })
            ->orderBy('name')
            ->paginate(12)           // jumlah kartu per halaman
            ->withQueryString();     // biar ?q= ikut di pagination

        return view('public.schools', [
            'schools' => $schools,
            'search'  => $search,
        ]);
    }

    // DETAIL SEKOLAH (punyamu tadi)
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
