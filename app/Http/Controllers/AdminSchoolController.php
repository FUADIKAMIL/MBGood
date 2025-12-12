<?php

namespace App\Http\Controllers;

use App\Models\School;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class AdminSchoolController extends Controller
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
        $search = $request->query('q');

        $schools = School::with(['vendors.user'])
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('region', 'like', "%{$search}%")
                        ->orWhere('address', 'like', "%{$search}%")
                        ->orWhereHas('vendors.user', function ($vendorQuery) use ($search) {
                            $vendorQuery->where('name', 'like', "%{$search}%");
                        });
                });
            })
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        $vendors = Vendor::with('user')->orderBy('company_name')->get();

        if ($request->wantsJson()) {
            $html = view('admin.schools.partials.list', [
                'schools' => $schools,
                'vendors' => $vendors,
            ])->render();

            return response()->json([
                'html' => $html,
                'total' => $schools->total(),
            ]);
        }

        return view('admin.schools.index', [
            'schools' => $schools,
            'vendors' => $vendors,
            'search' => $search,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:100|unique:schools,name',
            'region' => 'required|string|max:100',
            'address' => 'required|string|max:255',
            'vendor_id' => 'nullable|exists:vendors,id',
        ]);

        $school = School::create([
            'name' => $data['name'],
            'region' => $data['region'],
            'address' => $data['address'],
        ]);

        $this->syncVendor($school, $data['vendor_id'] ?? null);

        return redirect()
            ->route('admin.schools.index')
            ->with('success', 'Sekolah berhasil ditambahkan.');
    }

    public function update(Request $request, School $school)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100', Rule::unique('schools', 'name')->ignore($school->id)],
            'region' => 'required|string|max:100',
            'address' => 'required|string|max:255',
            'vendor_id' => 'nullable|exists:vendors,id',
        ]);

        $school->update([
            'name' => $data['name'],
            'region' => $data['region'],
            'address' => $data['address'],
        ]);

        $this->syncVendor($school, $data['vendor_id'] ?? null);

        return redirect()
            ->route('admin.schools.index')
            ->with('success', 'Sekolah berhasil diperbarui.');
    }

    public function destroy(School $school)
    {
        $school->vendors()->detach();
        $school->delete();

        return redirect()
            ->route('admin.schools.index')
            ->with('success', 'Sekolah berhasil dihapus.');
    }

    private function syncVendor(School $school, ?int $vendorId): void
    {
        if ($vendorId) {
            $school->vendors()->sync([$vendorId]);
            return;
        }

        $school->vendors()->detach();
    }
}
