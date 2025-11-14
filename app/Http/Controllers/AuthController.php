<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    public function loginPage()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return back()->with('error', 'Email atau password salah!');
        }

        // Simpan ke session
        session([
            'user_id' => $user->id,
            'role' => $user->role,
            'name' => $user->name
        ]);

        // Redirect sesuai role
        if ($user->role == 'admin') {
            return redirect('/admin/dashboard');
        }

        if ($user->role == 'vendor') {
            return redirect('/vendor/dashboard');
        }
    }

    public function logout()
    {
        Session::flush();
        return redirect('/login');
    }
}
