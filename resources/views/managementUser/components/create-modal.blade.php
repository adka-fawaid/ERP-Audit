<div id="addUserModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black bg-opacity-50 p-4">
    <div class="w-full max-w-lg overflow-y-auto rounded-2xl bg-white shadow-2xl">
        <div class="flex items-center justify-between border-b border-gray-100 px-6 py-5">
            <div>
                <h2 class="text-xl font-bold text-gray-900">Tambah User</h2>
                <p class="mt-1 text-sm text-gray-400">Tambahkan akses ke Dashboard Audit.</p>
            </div>
            <button type="button" onclick="closeModal()" class="flex h-9 w-9 items-center justify-center rounded-lg text-2xl text-gray-400 hover:bg-gray-100 hover:text-gray-700">
                &times;
            </button>
        </div>

        <form method="POST" action="{{ route('managementUser.store') }}" class="p-6">
            @csrf

            <div class="mb-5">
                <label for="nik" class="mb-2 block text-sm font-medium text-gray-700">Karyawan</label>
                <select id="nik" name="nik" required class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Pilih NIK - Nama</option>
                    @foreach($availableMembers as $member)
                        <option value="{{ $member->nik }}" {{ old('nik') === $member->nik ? 'selected' : '' }}>
                            {{ $member->nik }} - {{ $member->nama }}
                        </option>
                    @endforeach
                </select>
                <p class="mt-2 text-xs text-gray-400">
                    NIK akan dicocokkan dengan data master anggota perusahaan.
                </p>
            </div>
            <div class="mb-5">
                <label for="role" class="mb-2 block text-sm font-medium text-gray-700">Role Aplikasi</label>
                <select id="role" name="role" required class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="viewer" {{ old('role', 'viewer') === 'viewer' ? 'selected' : '' }}>Viewer</option>
                    <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                </select>
                <p class="mt-2 text-xs text-gray-400">
                    Role menentukan hak akses pengguna di Dashboard Audit.
                </p>
            </div>
            <div class="mb-2 flex items-start gap-3 rounded-lg border border-blue-100 bg-blue-50 p-4 text-blue-700">
                <i class="fa-solid fa-circle-info mt-0.5"></i>
                <div class="text-sm">
                    <p class="font-medium">Informasi</p>
                    <p class="mt-1 text-blue-600">
                        Nama dan status pengguna diambil otomatis dari master anggota perusahaan.
                    </p>
                </div>
            </div>
            <div class="mt-7 flex justify-end gap-3 border-t border-gray-100 pt-5">
                <button type="button" onclick="closeModal()" class="rounded-lg border border-gray-300 px-4 py-2.5 text-sm font-medium text-gray-600 hover:bg-gray-50">
                    Batal
                </button>
                <button type="submit" class="rounded-lg bg-blue-600 px-5 py-2.5 text-sm font-medium text-white transition hover:bg-blue-700">
                    Simpan User
                </button>
            </div>
        </form>
    </div>
</div>