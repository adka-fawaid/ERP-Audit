<div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
    <div class="px-6 py-5 border-b border-gray-100">
        <div class="flex items-center justify-between gap-6">
            <div class="shrink-0">
                <h2 class="font-semibold text-gray-900">Daftar User</h2>
                <p class="mt-1 text-xs text-gray-400">{{ $users->total() }} user terdaftar</p>
            </div>

            <form id="userFilterForm" method="GET" action="{{ route('managementUser.index') }}" class="flex shrink-0 items-center gap-2">
                <div class="relative flex items-center">
                    <input id="userSearch" type="text" name="search" value="{{ request('search') }}" placeholder=" Cari NIK, nama, email..." class="h-10 w-64 rounded-lg border border-gray-200 bg-white pl-9 pr-3 text-sm leading-none text-gray-700 outline-none transition placeholder:text-gray-400 focus:border-blue-400 focus:ring-2 focus:ring-blue-100">
                </div>

                <select name="auth_type" class="h-10 w-32 rounded-lg border border-gray-200 bg-white px-3 text-sm text-gray-600 outline-none transition focus:border-blue-400 focus:ring-2 focus:ring-blue-100">
                    <option value="">Semua Tipe</option>
                    <option value="company" {{ request('auth_type') === 'company' ? 'selected' : '' }}>Internal</option>
                    <option value="external" {{ request('auth_type') === 'external' ? 'selected' : '' }}>External</option>
                </select>

                <select name="role" class="h-10 w-32 rounded-lg border border-gray-200 bg-white px-3 text-sm text-gray-600 outline-none transition focus:border-blue-400 focus:ring-2 focus:ring-blue-100">
                    <option value="">Semua Role</option>
                    <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="viewer" {{ request('role') === 'viewer' ? 'selected' : '' }}>Viewer</option>
                </select>

                <select name="status" class="h-10 w-36 rounded-lg border border-gray-200 bg-white px-3 text-sm text-gray-600 outline-none transition focus:border-blue-400 focus:ring-2 focus:ring-blue-100">
                    <option value="">Semua Status</option>
                    <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Aktif</option>
                    <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Nonaktif</option>
                </select>

                @if (request()->hasAny(['search', 'auth_type', 'role', 'status']))
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
            <thead class="bg-gray-50 border-b border-gray-100">
                <tr>
                    <th class="text-left px-6 py-4 font-semibold text-gray-500">NIK</th>
                    <th class="text-left px-6 py-4 font-semibold text-gray-500">Nama</th>
                    <th class="text-left px-6 py-4 font-semibold text-gray-500">Tipe</th>
                    <th class="text-left px-6 py-4 font-semibold text-gray-500">Role</th>
                    <th class="text-left px-6 py-4 font-semibold text-gray-500">Status</th>
                    <th class="w-32 text-right px-6 py-4 font-semibold text-gray-500">Aksi</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-100">
                @forelse ($users as $user)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4">
                            @if ($user->nik)
                                <span class="font-medium text-gray-800">{{ $user->nik }}</span>
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>

                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center font-semibold text-sm">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>

                                <div>
                                    <p class="font-medium text-gray-800">{{ $user->name }}</p>

                                    @if ($user->email)
                                        <p class="text-xs text-gray-400">{{ $user->email }}</p>
                                    @endif
                                </div>
                            </div>
                        </td>

                        <td class="px-6 py-4">
                            @if ($user->auth_type === 'company')
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-blue-50 text-blue-700">Internal</span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-purple-50 text-purple-700">External</span>
                            @endif
                        </td>

                        <td class="px-6 py-4">
                            @if ($user->role === 'admin')
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-amber-50 text-amber-700">Admin</span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-600">Viewer</span>
                            @endif
                        </td>

                        <td class="px-6 py-4">
                            @if ($user->is_active)
                                <span class="inline-flex items-center gap-1.5 text-green-600 font-medium"><span class="w-2 h-2 rounded-full bg-green-500"></span> Aktif</span>
                            @else
                                <span class="inline-flex items-center gap-1.5 text-red-600 font-medium"><span class="w-2 h-2 rounded-full bg-red-500"></span> Nonaktif</span>
                            @endif
                        </td>

                        <td class="w-32 px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-1">
                                <button type="button" onclick="showUser({{ $user->id }})" class="w-9 h-9 rounded-lg flex items-center justify-center text-gray-500 transition hover:bg-blue-50" title="Lihat data">
                                    <i class="fa-solid fa-eye text-[15px]" style="color:#3b82f6;"></i>
                                </button>

                                <button type="button" onclick="editUser({{ $user->id }})" class="w-9 h-9 rounded-lg flex items-center justify-center text-gray-500 transition hover:bg-amber-50" title="Edit user">
                                    <i class="fa-solid fa-pen-to-square text-[15px]" style="color:#f59e0b;"></i>
                                </button>

                                @if ($user->id !== auth()->id() && $user->role !== 'admin')
                                    <button type="button" onclick="deleteUser({{ $user->id }}, '{{ addslashes($user->name) }}')" class="w-9 h-9 rounded-lg flex items-center justify-center text-gray-500 transition hover:bg-red-50" title="Hapus user">
                                        <i class="fa-solid fa-trash-can text-[15px]" style="color:#ef4444;"></i>
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center">
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