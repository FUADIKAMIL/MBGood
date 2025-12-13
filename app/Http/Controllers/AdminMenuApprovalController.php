<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdminMenuApprovalController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (!Auth::check() || Auth::user()->role !== 'admin') {
                abort(403);
            }

            return $next($request);
        });
    }

    public function index(Request $request)
    {
        $status = $request->query('status', 'pending');
        $vendorId = $request->query('vendor');

        $menus = Menu::with(['vendor.user', 'items', 'nutrition'])
            ->when($vendorId, fn ($query) => $query->where('vendor_id', $vendorId))
            ->when($status !== 'all', fn ($query) => $query->where('status', $status))
            ->latest()
            ->paginate(8)
            ->withQueryString();

        $statusCounts = Menu::select('status', DB::raw('count(*) as total'))
            ->when($vendorId, fn ($query) => $query->where('vendor_id', $vendorId))
            ->groupBy('status')
            ->pluck('total', 'status');

        $counts = [
            'all' => $statusCounts->sum(),
            'pending' => $statusCounts['pending'] ?? 0,
            'approved' => $statusCounts['approved'] ?? 0,
            'rejected' => $statusCounts['rejected'] ?? 0,
        ];

        $vendors = Vendor::with('user')->orderBy('company_name')->get();
        $selectedVendor = $vendorId ? $vendors->firstWhere('id', (int) $vendorId) : null;

        $vendorOptions = $vendors->map(function ($vendor) {
            $label = $vendor->user->name ?? $vendor->company_name ?? 'SPPG #' . $vendor->id;

            return [
                'id' => $vendor->id,
                'label' => $label,
            ];
        });

        $selectedVendorLabel = $selectedVendor
            ? ($selectedVendor->user->name ?? $selectedVendor->company_name ?? 'SPPG #' . $selectedVendor->id)
            : '';

        $proposedMenus = $selectedVendor
            ? Menu::with('vendor.user')
                ->where('vendor_id', $selectedVendor->id)
                ->where('status', 'pending')
                ->latest()
                ->get()
            : collect();

        return view('admin.menus.approval', [
            'menus' => $menus,
            'status' => $status,
            'statusCounts' => $counts,
            'vendors' => $vendors,
            'selectedVendor' => $selectedVendor,
            'vendorOptions' => $vendorOptions,
            'selectedVendorLabel' => $selectedVendorLabel,
            'proposedMenus' => $proposedMenus,
        ]);
    }

    public function approve(Menu $menu)
    {
        if ($menu->status === 'approved') {
            return back()->with('info', 'Menu sudah berstatus disetujui.');
        }

        $menu->update([
            'status' => 'approved',
            'rejection_reason' => null,
        ]);

        return back()->with('success', 'Menu berhasil disetujui.');
    }

    public function reject(Request $request, Menu $menu)
    {
        $data = $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);

        $menu->update([
            'status' => 'rejected',
            'rejection_reason' => $data['rejection_reason'],
        ]);

        return back()->with('success', 'Menu berhasil ditolak dengan catatan.');
    }
}
