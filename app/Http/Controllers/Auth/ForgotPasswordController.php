<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\UserPasswordReset;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ForgotPasswordController extends Controller
{
    /**
     * Display the forgot password form.
     */
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Handle the incoming password reset request by generating a new password
     * and sending it to the user's registered email.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'npk' => ['required', 'string'],
        ], [
            'npk.required' => 'Nomor NPK wajib diisi.',
        ]);

        $searchIdentifier = trim($request->input('npk'));

        // Lookup user by NPK (or by email as secondary convenience)
        $user = User::where('npk', $searchIdentifier)
            ->orWhere('email', strtolower($searchIdentifier))
            ->first();

        if (! $user) {
            return back()
                ->withErrors(['npk' => 'NPK atau akun tidak ditemukan dalam sistem.'])
                ->withInput();
        }

        if (empty($user->email) || ! filter_var($user->email, FILTER_VALIDATE_EMAIL)) {
            return back()
                ->withErrors(['npk' => 'Akun dengan NPK '.$user->npk.' belum memiliki email yang terdaftar. Silakan hubungi Administrator HR.'])
                ->withInput();
        }

        // Generate a new secure 10-character alphanumeric password
        $rawPassword = Str::password(10, letters: true, numbers: true, symbols: false, spaces: false);
        $user->password = Hash::make($rawPassword);
        $user->save();

        // Send email with the new credentials
        try {
            Mail::to($user->email)->send(new UserPasswordReset($user, $rawPassword));
        } catch (\Throwable $e) {
            return back()
                ->withErrors(['npk' => 'Gagal mengirim email reset password. Silakan hubungi Administrator HR.'])
                ->withInput();
        }

        // Mask email for privacy (e.g. joh***@company.com)
        $atIndex = strpos($user->email, '@');
        $maskedEmail = $atIndex > 2
            ? Str::mask($user->email, '*', 2, $atIndex - 2)
            : $user->email;

        return redirect()->route('login')->with(
            'status',
            'Password baru berhasil dikirim ke email terdaftar Anda ('.$maskedEmail.'). Silakan cek kotak masuk atau folder spam Anda.'
        );
    }
}
