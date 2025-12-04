<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

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
    public function index()
    {
        $vendors = Vendor::with('user')
            ->orderBy('company_name')
            ->get();

        return view('admin.vendors.index', compact('vendors'));
    }

    // Simpan akun SPPG baru
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'         => 'required|string|max:255',
            'email'        => 'required|email|unique:users,email',
            'password'     => 'required|string|min:6',
            'company_name' => 'required|string|max:255',
            'contact'      => 'nullable|string|max:50',
        ]);

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
            'company_name' => $data['company_name'],
            'contact'      => $data['contact'],
        ]);

        return redirect()
            ->route('admin.vendors.index')
            ->with('success', 'Akun SPPG berhasil dibuat.');
    }

    // Hapus akun SPPG (vendor + user)
    public function destroy(Vendor $vendor)
    {
        $user = $vendor->user;

        $vendor->delete();

        if ($user) {
            $user->delete();
        }

        return redirect()
            ->route('admin.vendors.index')
            ->with('success', 'Akun SPPG berhasil dihapus.');
    }
}
