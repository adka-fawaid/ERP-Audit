<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\QadUserRole;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = QadUserRole::query()
            ->join('mst_anggota', 'mst_anggota.nik', '=', 'qad_user_roles.nik')
            ->select(
                'qad_user_roles.id',
                'qad_user_roles.nik',
                'qad_user_roles.role',
                'qad_user_roles.status',
                'mst_anggota.nama',
                'mst_anggota.status_hapus',
                'mst_anggota.freeze'
            );
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('qad_user_roles.nik', 'like', "%{$search}%")
                    ->orWhere('mst_anggota.nama', 'like', "%{$search}%");
            });
        }
        if ($request->filled('role')) {
            $query->where('qad_user_roles.role', $request->role);
        }
        if ($request->filled('status')) {
            $query->where('qad_user_roles.status', $request->status);
        }
        $users = $query
            ->orderBy('mst_anggota.nama')
            ->paginate(10)
            ->withQueryString();

        $availableMembers = User::query()
            ->where('status_hapus', 1)
            ->where('freeze', 0)
            ->whereNotExists(function ($query) {
                $query->selectRaw('1')
                    ->from('qad_user_roles')
                    ->whereColumn('qad_user_roles.nik', 'mst_anggota.nik');
            })
            ->orderBy('nama')
            ->get(['nik', 'nama']);

        return view('managementUser.index', compact('users', 'availableMembers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nik' => ['required', 'string', 'max:255', 'exists:mst_anggota,nik'],
            'role' => ['required', 'in:admin,viewer'],
        ]);
        $member = User::where('nik', $validated['nik'])->first();
        if (!$member) {
            return back()
                ->withInput()
                ->with('error', 'NIK tidak ditemukan di master anggota.');
        }
        if (!$member->is_active) {
            return back()
                ->withInput()
                ->with('error', 'User tersebut sedang dinonaktifkan di sistem perusahaan.');
        }
        if (QadUserRole::where('nik', $validated['nik'])->exists()) {
            return back()
                ->withInput()
                ->with('error', 'NIK tersebut sudah terdaftar di QAD Audit.');
        }
        $userRole = QadUserRole::create([
            'nik' => $validated['nik'],
            'role' => $validated['role'],
            'status' => 'active',
        ]);
        ActivityLog::record(
            $request,
            'CREATE_USER',
            "Menambahkan akses QAD untuk NIK {$userRole->nik} dengan role {$userRole->role}."
        );
        return redirect()
            ->route('managementUser.index')
            ->with('success', 'User berhasil ditambahkan.');
    }

    public function show(QadUserRole $user)
    {
        $member = User::where('nik', $user->nik)->first();
        return response()->json([
            'id' => $user->id,
            'nik' => $user->nik,
            'name' => $member?->name,
            'role' => $user->role,
            'status' => $user->status,
            'is_active' => $user->status === 'active',
        ]);
    }
    public function update(Request $request, QadUserRole $user)
    {
        $validated = $request->validate([
            'role' => ['required', 'in:admin,viewer'],
            'status' => ['required', 'in:active,inactive'],
        ]);
        $oldRole = $user->role;
        $oldStatus = $user->status;
        $user->update([
            'role' => $validated['role'],
            'status' => $validated['status'],
        ]);
        if ($oldRole !== $user->role) {
            ActivityLog::record(
                $request,
                'CHANGE_ROLE',
                "Mengubah role NIK {$user->nik} dari {$oldRole} menjadi {$user->role}."
            );
        }
        if ($oldStatus !== $user->status) {
            ActivityLog::record(
                $request,
                'UPDATE_USER',
                "Mengubah status akses QAD NIK {$user->nik} dari {$oldStatus} menjadi {$user->status}."
            );
        }
        return redirect()
            ->route('managementUser.index')
            ->with('success', 'Data user berhasil diperbarui.');
    }

    public function destroy(Request $request, QadUserRole $user)
    {
        $nik = $user->nik;
        $user->delete();
        ActivityLog::record(
            $request,
            'DELETE_USER',
            "Menghapus akses QAD untuk NIK {$nik}."
        );
        return redirect()
            ->route('managementUser.index')
            ->with('success', 'Akses user berhasil dihapus dari QAD.');
    }
}