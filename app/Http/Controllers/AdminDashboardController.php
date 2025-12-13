<?php

namespace App\Http\Controllers;

use App\Models\DailyMenu;
use App\Models\Menu;
use App\Models\School;
use App\Models\Vendor;
use Carbon\Carbon;

class AdminDashboardController extends Controller
{
    public function __invoke()
    {
        $menuQuery = Menu::query();

        $stats = [
            'schools' => School::count(),
            'vendors' => Vendor::count(),
            'pendingMenus' => Menu::where('status', 'pending')->count(),
            'totalMenus' => $menuQuery->count(),
            'unassignedSchools' => School::doesntHave('vendors')->count(),
        ];

        $recentMenus = Menu::with(['vendor.user'])
            ->latest()
            ->take(6)
            ->get();

        $recentVendors = Vendor::with('user')
            ->latest()
            ->take(5)
            ->get();

        $upcomingDailyMenus = DailyMenu::with(['school', 'menu.vendor'])
            ->whereDate('date', '>=', Carbon::today())
            ->orderBy('date')
            ->take(7)
            ->get();

        return view('admin.dashboardadmin', [
            'stats' => $stats,
            'recentMenus' => $recentMenus,
            'recentVendors' => $recentVendors,
            'upcomingDailyMenus' => $upcomingDailyMenus,
        ]);
    }
}
