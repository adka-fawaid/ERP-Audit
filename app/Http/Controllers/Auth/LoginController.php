<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
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
        $user = User::where('nik', $credentials['identity'])->first();
        if (!$user || !Hash::check($credentials['password'], $user->password_hash ?? '')) {
            return back()
                ->withErrors([
                    'identity' => 'NIK atau password salah.',
                ])
                ->withInput($request->only('identity'));
        }
        if (!$user->is_active) {
            return back()
                ->withErrors([
                    'identity' => 'Akun Anda tidak aktif di sistem perusahaan.',
                ])
                ->withInput($request->only('identity'));
        }
        $qadRole = $user->qadRole;
        if (!$qadRole) {
            return back()
                ->withErrors([
                    'identity' => 'Anda belum terdaftar sebagai pengguna QAD Audit.',
                ])
                ->withInput($request->only('identity'));
        }
        if ($qadRole->status !== 'active') {
            return back()
                ->withErrors([
                    'identity' => 'Akses Anda ke QAD Audit sedang dinonaktifkan.',
                ])
                ->withInput($request->only('identity'));
        }
        Auth::login($user, $request->boolean('remember'));
        $request->session()->regenerate();
        ActivityLog::record($request, 'LOGIN', 'Berhasil login.');
        return redirect()->intended('dashboard');
    }

    public function logout(Request $request)
    {
        ActivityLog::record($request, 'LOGOUT', 'Berhasil logout.');
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}