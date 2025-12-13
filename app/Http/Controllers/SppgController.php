<?php

namespace App\Http\Controllers;

use App\Models\DailyMenu;
use App\Models\Menu;
use App\Models\School;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
        $vendor = Vendor::where('user_id', Auth::id())->firstOrFail();

        $menuSubmissions = Menu::with(['items', 'nutrition'])
            ->where('vendor_id', $vendor->id)
            ->latest()
            ->get();

        return view('sppg.ajukan', [
            'schools' => School::all(),
            'menuSubmissions' => $menuSubmissions,
        ]);
    }


    /* ===========================================
       AJUKAN MENU BARU (KE ADMIN)
    =========================================== */
    public function storeMenu(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'items' => 'required|array|min:1',
            'items.*.name' => 'required|string|max:100',
            'items.*.portion' => 'required|string|max:50',
            'items.*.category' => 'required|in:carbs,protein,vegetable,fruit,drink,other',
            'calories' => 'required|integer|min:0',
            'protein' => 'required|integer|min:0',
            'fat' => 'required|integer|min:0',
            'carbs' => 'required|integer|min:0',
            'vitamins' => 'nullable|string',
        ]);

        $vendor = Vendor::where('user_id', Auth::id())->firstOrFail();

        DB::transaction(function () use ($data, $vendor) {
            $menu = Menu::create([
                'vendor_id' => $vendor->id,
                'title'      => $data['title'],
                'description'=> $data['description'],
                'status'     => 'pending',
            ]);

            $itemsPayload = collect($data['items'])
                ->map(fn ($item) => [
                    'name' => $item['name'],
                    'portion' => $item['portion'],
                    'category' => $item['category'],
                ])->all();

            $menu->items()->createMany($itemsPayload);

            $vitamins = $data['vitamins']
                ? collect(explode(',', $data['vitamins']))
                    ->map(fn ($vitamin) => trim($vitamin))
                    ->filter()
                    ->values()
                    ->all()
                : null;

            $menu->nutrition()->create([
                'calories' => $data['calories'],
                'protein' => $data['protein'],
                'fat' => $data['fat'],
                'carbs' => $data['carbs'],
                'vitamins' => $vitamins,
            ]);
        });

        return back()->with('success', 'Menu berhasil diajukan ke admin!');
    }

    public function cancelMenu(Menu $menu)
    {
        $vendor = Vendor::where('user_id', Auth::id())->firstOrFail();

        if ($menu->vendor_id !== $vendor->id) {
            abort(403);
        }

        if ($menu->status !== 'pending') {
            return back()->with('info', 'Menu tidak dapat dibatalkan karena sudah diproses admin.');
        }

        $menu->delete();

        return back()->with('success', 'Pengajuan menu berhasil dibatalkan.');
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
