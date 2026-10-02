<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'identity' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $user = User::where(function ($query) use ($credentials) {
            $query->where('nik', $credentials['identity'])
                  ->orWhere('email', $credentials['identity']);
        })
        ->where('is_active', true)
        ->first();

        if (!$user) {
            return back()
                ->withErrors([
                    'identity' => 'Akun tidak ditemukan atau tidak aktif.',
                ])
                ->withInput($request->only('identity'));
        }

        /*
         * EXTERNAL
         *
         * Untuk sementara masih menggunakan database lokal.
         * Nanti bagian ini diganti dengan authentication API
         * untuk user internal.
         */
        if ($user->auth_type === 'external') {

            if (!Hash::check($credentials['password'], $user->password)) {
                return back()
                    ->withErrors([
                        'identity' => 'NIK/email atau password salah.',
                    ])
                    ->withInput($request->only('identity'));
            }
        }

        /*
         * COMPANY
         *
         * Sementara dummy:
         * password = password123
         *
         * Nanti diganti menjadi:
         *
         * CompanyAuthService
         *       ↓
         * Authentication API perusahaan
         */
        if ($user->auth_type === 'company') {
            if ($credentials['password'] !== 'password123') {
                return back()
                    ->withErrors([
                        'identity' => 'NIK atau password salah.',
                    ])
                    ->withInput($request->only('identity'));
            }
        }
        Auth::login($user, $request->boolean('remember'));
        $request->session()->regenerate();
        return redirect()->intended('dashboard');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}