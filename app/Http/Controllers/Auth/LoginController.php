<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class LoginController extends Controller
{
    public function create(): View
    {
        return view('auth.login');
    }

    public function store(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'npk' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()
                ->withErrors(['npk' => 'NPK atau password tidak sesuai.'])
                ->onlyInput('npk');
        }

        $request->session()->regenerate();

        // Update last login time
        $user = Auth::user();
        $user->last_login_at = now();
        $user->save();

        return redirect()->route('dashboard');
    }

    public function destroy(Request $request): Response|RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        if ($request->header('HX-Request')) {
            return response('', 200)->header('HX-Redirect', route('login'));
        }

        return redirect()->route('login');
    }
}
