<div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
    <div class="border-b border-gray-100 px-6 py-5">
        <div class="flex items-center justify-between gap-6">
            <div class="shrink-0">
                <h2 class="font-semibold text-gray-900">Daftar User</h2>
                <p class="mt-1 text-xs text-gray-400">{{ $users->total() }} user terdaftar</p>
            </div>

            <form method="GET" action="{{ route('managementUser.index') }}" class="flex shrink-0 items-center gap-2">
                <div class="relative">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari NIK atau nama..." class="h-10 w-64 rounded-lg border border-gray-200 bg-white px-3 text-sm text-gray-700 outline-none transition placeholder:text-gray-400 focus:border-blue-400 focus:ring-2 focus:ring-blue-100">
                </div>

                <select name="role" class="h-10 w-32 rounded-lg border border-gray-200 bg-white px-3 text-sm text-gray-600 outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-100">
                    <option value="">Semua Role</option>
                    <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="viewer" {{ request('role') === 'viewer' ? 'selected' : '' }}>Viewer</option>
                </select>

                <select name="status" class="h-10 w-36 rounded-lg border border-gray-200 bg-white px-3 text-sm text-gray-600 outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-100">
                    <option value="">Semua Status</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Aktif</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                </select>

                @if (request()->hasAny(['search', 'role', 'status']))
                    <a href="{{ route('managementUser.index') }}" class="flex h-10 shrink-0 items-center gap-2 rounded-lg border border-gray-200 px-3 text-sm font-medium text-gray-600 transition hover:bg-gray-50">
                        <i class="fa-solid fa-rotate-left text-[13px]"></i>
                        Reset
                    </a>
                @endif
            </form>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="border-b border-gray-100 bg-gray-50">
                <tr>
                    <th class="px-6 py-4 text-left font-semibold text-gray-500">NIK</th>
                    <th class="px-6 py-4 text-left font-semibold text-gray-500">Nama</th>
                    <th class="px-6 py-4 text-left font-semibold text-gray-500">Role</th>
                    <th class="px-6 py-4 text-left font-semibold text-gray-500">Status</th>
                    <th class="w-32 px-6 py-4 text-right font-semibold text-gray-500">Aksi</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-100">
                @forelse ($users as $user)
                    <tr class="transition hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <span class="font-medium text-gray-800">{{ $user->nik }}</span>
                        </td>

                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="flex h-9 w-9 items-center justify-center rounded-full bg-blue-50 text-sm font-semibold text-blue-600">
                                    {{ strtoupper(substr($user->nama, 0, 1)) }}
                                </div>

                                <p class="font-medium text-gray-800">{{ $user->nama }}</p>
                            </div>
                        </td>

                        <td class="px-6 py-4">
                            @if ($user->role === 'admin')
                                <span class="inline-flex items-center rounded-full bg-amber-50 px-2.5 py-1 text-xs font-medium text-amber-700">
                                    Admin
                                </span>
                            @else
                                <span class="inline-flex items-center rounded-full bg-gray-100 px-2.5 py-1 text-xs font-medium text-gray-600">
                                    Viewer
                                </span>
                            @endif
                        </td>

                        <td class="px-6 py-4">
                            @if ($user->status === 'active')
                                <span class="inline-flex items-center gap-1.5 font-medium text-green-600">
                                    <span class="h-2 w-2 rounded-full bg-green-500"></span>
                                    Aktif
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 font-medium text-red-600">
                                    <span class="h-2 w-2 rounded-full bg-red-500"></span>
                                    Nonaktif
                                </span>
                            @endif
                        </td>

                        <td class="w-32 px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-1">
                                <button type="button" onclick="showUser({{ $user->id }})" class="flex h-9 w-9 items-center justify-center rounded-lg text-gray-500 transition hover:bg-blue-50" title="Lihat data">
                                    <i class="fa-solid fa-eye text-[15px]" style="color:#3b82f6;"></i>
                                </button>
                                <button type="button" onclick="editUser({{ $user->id }})" class="flex h-9 w-9 items-center justify-center rounded-lg text-gray-500 transition hover:bg-amber-50" title="Edit role">
                                    <i class="fa-solid fa-pen-to-square text-[15px]" style="color:#f59e0b;"></i>
                                </button>
                                @if ($user->nik !== auth()->user()->nik)
                                    <button type="button" onclick="deleteUser({{ $user->id }}, '{{ addslashes($user->nama) }}')" class="flex h-9 w-9 items-center justify-center rounded-lg text-gray-500 transition hover:bg-red-50" title="Hapus akses">
                                        <i class="fa-solid fa-trash-can text-[15px]" style="color:#ef4444;"></i>
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center">
                            <div class="text-gray-400">
                                <i class="fa-solid fa-users mb-3 text-4xl text-blue-300"></i>
                                <p class="text-sm">Belum ada user.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if ($users->hasPages())
        <div class="border-t border-gray-100 px-6 py-4">
            {{ $users->links() }}
        </div>
    @endif
</div>