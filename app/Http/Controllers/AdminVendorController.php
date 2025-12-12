<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Vendor;
use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class AdminVendorController extends Controller
{
    // Opsional: pastikan hanya admin yang boleh akses
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (!Auth::check() || Auth::user()->role !== 'admin') {
                abort(403);
            }
            return $next($request);
        });
    }

    // Tampilkan list + form tambah akun SPPG
    public function index(Request $request)
    {
        $search = $request->query('q');

        $vendors = Vendor::with(['user', 'schools'])
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->whereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    })->orWhere('contact', 'like', "%{$search}%");
                });
            })
            ->orderBy('company_name')
            ->paginate(8)
            ->withQueryString();

        $schools = School::orderBy('name')->get();

        $assignedSchoolIds = $vendors->pluck('schools')->flatten()->pluck('id')->unique()->toArray();

        return view('admin.vendors.index', [
            'vendors' => $vendors,
            'schools' => $schools,
            'assignedSchoolIds' => $assignedSchoolIds,
            'search'  => $search,
        ]);
    }

    // Simpan akun SPPG baru
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'email'       => 'required|email|max:255|unique:users,email',
            'password'    => 'required|string|min:6',
            'contact'     => 'nullable|string|max:50',
            'school_ids'  => 'array',
            'school_ids.*'=> 'exists:schools,id',
        ]);

        $this->ensureSchoolsAvailable($data['school_ids'] ?? []);

        // Buat user dengan role vendor
        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
            'role'     => 'vendor',
        ]);

        // Buat data vendor
        Vendor::create([
            'user_id'      => $user->id,
            'company_name' => $data['name'],
            'contact'      => $data['contact'],
        ])->schools()
            ->sync($data['school_ids'] ?? []);

        return redirect()
            ->route('admin.vendors.index')
            ->with('success', 'Akun SPPG berhasil dibuat.');
    }

    public function update(Request $request, Vendor $vendor)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'email'       => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($vendor->user_id),
            ],
            'password'    => 'nullable|string|min:6',
            'contact'     => 'nullable|string|max:50',
            'school_ids'  => 'array',
            'school_ids.*'=> 'exists:schools,id',
        ]);

        $this->ensureSchoolsAvailable($data['school_ids'] ?? [], $vendor->id);

        $user = $vendor->user;

        $user->name = $data['name'];
        $user->email = $data['email'];
        if (!empty($data['password'])) {
            $user->password = Hash::make($data['password']);
        }
        $user->save();

        $vendor->update([
            'company_name' => $data['name'],
            'contact'      => $data['contact'],
        ]);

        $vendor->schools()->sync($data['school_ids'] ?? []);

        return redirect()
            ->route('admin.vendors.index')
            ->with('success', 'Akun SPPG berhasil diperbarui.');
    }

    // Hapus akun SPPG (vendor + user)
    public function destroy(Vendor $vendor)
    {
        $user = $vendor->user;

        $vendor->schools()->detach();

        $vendor->delete();

        if ($user) {
            $user->delete();
        }

        return redirect()
            ->route('admin.vendors.index')
            ->with('success', 'Akun SPPG berhasil dihapus.');
    }

    private function ensureSchoolsAvailable(array $schoolIds = [], ?int $ignoreVendorId = null): void
    {
        if (empty($schoolIds)) {
            return;
        }

        $conflictedIds = DB::table('vendor_schools')
            ->whereIn('school_id', $schoolIds)
            ->when($ignoreVendorId, fn ($query) => $query->where('vendor_id', '!=', $ignoreVendorId))
            ->pluck('school_id')
            ->unique();

        if ($conflictedIds->isEmpty()) {
            return;
        }

        $names = School::whereIn('id', $conflictedIds)->pluck('name')->implode(', ');

        throw ValidationException::withMessages([
            'school_ids' => 'Sekolah berikut sudah ditangani SPPG lain: ' . $names,
        ]);
    }
}
