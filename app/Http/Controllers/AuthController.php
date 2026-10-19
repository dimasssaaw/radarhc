<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class AuthController extends Controller
{
    // Menampilkan halaman React Login
    public function showLoginForm()
    {
        return Inertia::render('Auth/Login');
    }

    // Memproses data login yang dikirim dari React
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'identifier' => ['required', 'string'],
            'password' => ['required'],
        ]);

        // Menggunakan identifier untuk mengecek username, email, atau NPK
        // Ubah 'email' menjadi nama kolom yang sesuai di database kamu
        $loginType = filter_var($request->identifier, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        if (Auth::attempt([$loginType => $credentials['identifier'], 'password' => $credentials['password']], $request->boolean('remember'))) {
            $request->session()->regenerate();

            // Jika sukses, arahkan ke rute dashboard
            return redirect()->intended('/radar');
        }

        // Jika gagal, kembalikan pesan error ke komponen React
        return back()->withErrors([
            'identifier' => 'Kredensial yang diberikan tidak cocok dengan data kami.',
        ])->onlyInput('identifier');
    }

    // Memproses Logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        if ($request->header('HX-Request')) {
            return response('', 200)->header('HX-Redirect', route('login'));
        }

        return redirect('/');
    }
}
