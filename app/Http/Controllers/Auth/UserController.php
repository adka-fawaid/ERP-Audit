<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\CompanyAuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nik', 'like', "%{$search}%")
                ->orWhere('name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%");
            });
        }
        if ($request->filled('auth_type')) {
            $query->where('auth_type', $request->auth_type);
        }
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }
        if ($request->filled('status')) {
            $query->where('is_active', $request->status);
        }
        $users = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();
        return view('managementUser.index', compact('users'));
    }

    public function store(Request $request, CompanyAuthService $companyAuth)
    {
        $request->validate([
            'auth_type' => ['required', 'in:company,external'],
            'role' => ['required', 'in:admin,viewer'],
        ]);
        if ($request->auth_type === 'company') {
            $request->validate([
                'nik' => ['required', 'string', 'max:50'],
            ]);
            $existingUser = User::where('nik', $request->nik)->first();
            if ($existingUser) {
                return back()
                    ->withErrors([
                        'nik' => 'NIK tersebut sudah memiliki akun.',
                    ])
                    ->withInput();
            }
            $employee = $companyAuth->findEmployeeByNik($request->nik);
            if (!$employee) {
                return back()
                    ->withErrors([
                        'nik' => 'NIK tidak ditemukan pada data perusahaan.',
                    ])
                    ->withInput();
            }
            User::create([
                'auth_type' => 'company',
                'nik' => $employee['nik'],
                'name' => $employee['name'],
                'email' => null,
                'password' => null,
                'role' => $request->role,
                'is_active' => true,
            ]);
            return redirect()
                ->route('managementUser.index')
                ->with('success', 'User internal berhasil ditambahkan.');
        }
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
        ]);
        User::create([
            'auth_type' => 'external',
            'nik' => null,
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'viewer',
            'is_active' => true,
        ]);
        return redirect()
            ->route('managementUser.index')
            ->with('success', 'External user berhasil ditambahkan.');
    }

    public function show(User $user)
    {
        return response()->json([
            'id' => $user->id,
            'nik' => $user->nik,
            'name' => $user->name,
            'email' => $user->email,
            'auth_type' => $user->auth_type,
            'role' => $user->role,
            'is_active' => $user->is_active,
            'created_at' => $user->created_at?->format('d/m/Y H:i'),
            'updated_at' => $user->updated_at?->format('d/m/Y H:i'),
        ]);
    }

    public function update(Request $request, User $user, CompanyAuthService $companyAuth)
    {
        $request->validate([
            'role' => ['required', 'in:admin,viewer'],
            'is_active' => ['required', 'boolean'],
        ]);
        if ($user->auth_type === 'company') {
            $request->validate([
                'nik' => ['required', 'string', 'max:50'],
            ]);
            if ($request->nik !== $user->nik) {
                $existingUser = User::where('nik', $request->nik)
                    ->where('id', '!=', $user->id)
                    ->first();
                if ($existingUser) {
                    return back()
                        ->withErrors([
                            'nik' => 'NIK tersebut sudah digunakan user lain.',
                        ]);
                }
                $employee = $companyAuth->findEmployeeByNik($request->nik);
                if (!$employee) {
                    return back()
                        ->withErrors([
                            'nik' => 'NIK tidak ditemukan pada data perusahaan.',
                        ]);
                }
                $user->nik = $employee['nik'];
                $user->name = $employee['name'];
            }
        }
        if ($user->auth_type === 'external') {
            $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => [
                    'required',
                    'email',
                    'max:255',
                    'unique:users,email,' . $user->id,
                ],
            ]);
            $user->name = $request->name;
            $user->email = $request->email;
            if ($request->filled('password')) {
                $request->validate([
                    'password' => ['string', 'min:8'],
                ]);
                $user->password = Hash::make($request->password);
            }
            // External tetap Viewer
            $user->role = 'viewer';
        }
        $user->role = $request->role;
        $user->is_active = $request->boolean('is_active');
        $user->save();
        return redirect()
            ->route('managementUser.index')
            ->with('success', 'Data user berhasil diperbarui.');
    }
    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()
                ->withErrors([
                    'user' => 'Akun yang sedang digunakan tidak dapat dihapus.',
                ]);
        }
        if ($user->role === 'admin') {
            return back()
                ->withErrors([
                    'user' => 'User dengan role Admin tidak dapat dihapus. Ubah role menjadi Viewer terlebih dahulu.',
                ]);
        }
        $user->delete();
        return redirect()
            ->route('managementUser.index')
            ->with('success', 'User berhasil dihapus.');
    }
}