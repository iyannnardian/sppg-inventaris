<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->ensureIsNotRateLimited();

        $user = User::where('username', $request->username)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            RateLimiter::hit($request->throttleKey());

            throw ValidationException::withMessages([
                'username' => 'Username atau kata sandi yang Anda masukkan salah. Silakan periksa kembali.',
            ]);
        }

        // Batas waktu sesi aktif berdasarkan config session lifetime (dalam detik)
        $sessionLifetime = config('session.lifetime', 120) * 60;
        $activeCutoff = time() - $sessionLifetime;

        // Hapus sesi kadaluarsa milik user tersebut dari database
        DB::table('sessions')
            ->where('user_id', $user->id_user)
            ->where('last_activity', '<', $activeCutoff)
            ->delete();

        // Cek apakah akun sedang aktif di perangkat lain (masih dalam rentang waktu sesi aktif)
        $hasActiveOtherSession = DB::table('sessions')
            ->where('user_id', $user->id_user)
            ->where('id', '!=', $request->session()->getId())
            ->where('last_activity', '>=', $activeCutoff)
            ->exists();

        if ($hasActiveOtherSession && ! $request->boolean('confirm_kick')) {
            return back()->withInput([
                'username' => $request->username,
                'password' => $request->password,
            ])->with('active_session_found', true);
        }

        $request->authenticate();

        $request->session()->regenerate();

        if ($request->filled('password')) {
            Auth::logoutOtherDevices($request->password);
        }

        DB::table('sessions')
            ->where('user_id', Auth::id())
            ->where('id', '!=', $request->session()->getId())
            ->delete();

        return redirect()->intended(route('dashboard', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
