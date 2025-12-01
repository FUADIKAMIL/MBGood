<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\School;
use App\Models\DailyMenu;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SppgController extends Controller
{
    public function index()
    {
        // ambil vendor yang login
        $vendor = Vendor::where('user_id', Auth::id())->first();

        // sekolah yang dipegang vendor
        $schools = $vendor->schools()->get();

        return view('sppg.riwayat', [
            'schools' => $schools,

            // menu approved milik vendor
            'approvedMenus' => Menu::where('vendor_id', $vendor->id)
                                   ->where('status', 'approved')
                                   ->get(),

            // riwayat daily menu
            'dailyMenus' => DailyMenu::whereHas('menu', function ($q) use ($vendor) {
                                $q->where('vendor_id', $vendor->id);
                            })->latest()->get(),
        ]);
    }

    public function ajukanForm()
    {
        return view('sppg.ajukan', [
            'schools' => School::all()
        ]);
    }


    /* ===========================================
       AJUKAN MENU BARU (KE ADMIN)
    =========================================== */
    public function storeMenu(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required',
        ]);

        $vendor = Vendor::where('user_id', Auth::id())->first();

        Menu::create([
            'vendor_id' => $vendor->id,
            'title'      => $request->title,
            'description'=> $request->description,
            'status'     => 'pending', // admin approve
        ]);

        return back()->with('success', 'Menu berhasil diajukan ke admin!');
    }


    /* ===========================================
       INPUT MENU HARIAN (KE SEKOLAH)
    =========================================== */
    public function storeDaily(Request $request)
    {
        $request->validate([
            'school_id' => 'required',
            'menu_id'   => 'required',
            'date'      => 'required|date',
        ]);

        DailyMenu::create([
            'school_id' => $request->school_id,
            'menu_id'   => $request->menu_id,
            'date'      => $request->date,
        ]);

        return back()->with('success', 'Menu harian berhasil ditambahkan!');
    }

    public function dailyForm()
    {
        $vendorId = Auth::user()->vendor->id;

        return view('sppg.menu', [
            'schools' => \App\Models\School::all(),
            'approvedMenus' => \App\Models\Menu::where('vendor_id', $vendorId)
                                ->where('status', 'approved')
                                ->get()
        ]);
    }
}
