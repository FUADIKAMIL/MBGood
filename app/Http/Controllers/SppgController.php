<?php

namespace App\Http\Controllers;

use App\Models\DailyMenu;
use App\Models\Menu;
use App\Models\School;
use App\Models\Vendor;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SppgController extends Controller
{
    public function dashboard()
    {
        $vendor = Vendor::with('schools')
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $menuQuery = Menu::where('vendor_id', $vendor->id);

        $stats = [
            'assignedSchools' => $vendor->schools->count(),
            'totalMenus' => (clone $menuQuery)->count(),
            'approvedMenus' => (clone $menuQuery)->where('status', 'approved')->count(),
            'pendingMenus' => (clone $menuQuery)->where('status', 'pending')->count(),
            'rejectedMenus' => (clone $menuQuery)->where('status', 'rejected')->count(),
            'dailyMenus' => DailyMenu::whereHas('menu', fn ($q) => $q->where('vendor_id', $vendor->id))->count()
        ];

        $recentMenus = Menu::with(['items', 'nutrition'])
            ->where('vendor_id', $vendor->id)
            ->latest()
            ->take(5)
            ->get();

        $upcomingSchedules = DailyMenu::with(['school', 'menu'])
            ->whereHas('menu', fn ($q) => $q->where('vendor_id', $vendor->id))
            ->whereDate('date', '>=', Carbon::today())
            ->orderBy('date')
            ->take(7)
            ->get();

        return view('sppg.dashboard', [
            'vendor' => $vendor,
            'stats' => $stats,
            'recentMenus' => $recentMenus,
            'upcomingSchedules' => $upcomingSchedules,
        ]);
    }

    public function index(Request $request)
    {
        // ambil vendor yang login
        $vendor = Vendor::where('user_id', Auth::id())->first();

        // sekolah yang dipegang vendor
        $schools = $vendor->schools()->get();

        $selectedDate = $request->query('date');

        $dailyMenusQuery = DailyMenu::with(['menu', 'school'])
            ->whereHas('menu', function ($q) use ($vendor) {
                $q->where('vendor_id', $vendor->id);
            });

        if ($selectedDate) {
            $dailyMenusQuery->whereDate('date', $selectedDate);
        }

        $dailyMenus = $dailyMenusQuery->orderByDesc('date')->get();

        $availableDates = DailyMenu::whereHas('menu', function ($q) use ($vendor) {
                $q->where('vendor_id', $vendor->id);
            })
            ->select('date')
            ->distinct()
            ->orderByDesc('date')
            ->pluck('date');

        return view('sppg.riwayat', [
            'schools' => $schools,
            // menu approved milik vendor
            'approvedMenus' => Menu::where('vendor_id', $vendor->id)
                                   ->where('status', 'approved')
                                   ->get(),
            'dailyMenus' => $dailyMenus,
            'availableDates' => $availableDates,
            'selectedDate' => $selectedDate,
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

        $exists = DailyMenu::where('school_id', $request->school_id)
            ->where('date', $request->date)
            ->exists();

        if ($exists) {
            return back()
                ->withErrors(['date' => 'Sekolah tersebut sudah memiliki menu pada tanggal ini.'])
                ->withInput();
        }

        try {
            DailyMenu::create([
                'school_id' => $request->school_id,
                'menu_id'   => $request->menu_id,
                'date'      => $request->date,
            ]);
        } catch (QueryException $e) {
            if ((int) $e->getCode() === 23000) {
                return back()
                    ->withErrors(['date' => 'Sekolah tersebut sudah memiliki menu pada tanggal ini.'])
                    ->withInput();
            }

            throw $e;
        }

        return back()->with('success', 'Menu harian berhasil ditambahkan!');
    }

    public function dailyForm()
    {
        $vendor = Auth::user()->vendor;

        return view('sppg.menu', [
            'schools' => $vendor?->schools()->orderBy('name')->get() ?? collect(),
            'approvedMenus' => \App\Models\Menu::where('vendor_id', $vendor->id)
                                ->where('status', 'approved')
                                ->get()
        ]);
    }
}
