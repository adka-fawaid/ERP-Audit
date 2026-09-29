<div id="addUserModal" class="fixed inset-0 z-50 bg-black bg-opacity-50 hidden items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between px-6 py-5 border-b border-gray-100">
            <div>
                <h2 class="text-xl font-bold text-gray-900">Tambah User</h2>
                <p class="text-sm text-gray-400 mt-1">Tambahkan akses ke Dashboard Audit.</p>
            </div>
            <button type="button" onclick="closeModal()" class="w-9 h-9 rounded-lg text-gray-400 hover:bg-gray-100 hover:text-gray-700 flex items-center justify-center text-2xl">
                &times;
            </button>
        </div>
        <form method="POST" action="{{ route('managementUser.store') }}" class="p-6">
            @csrf
            <div class="mb-6">
                <label for="auth_type" class="block text-sm font-medium text-gray-700 mb-2">Tipe User</label>
                <select id="auth_type" name="auth_type" onchange="toggleUserType()" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="company">Internal / Karyawan</option>
                    <option value="external">External / Auditor</option>
                </select>
            </div>
            <div id="companyFields">
                <div class="mb-5">
                    <label for="nik" class="block text-sm font-medium text-gray-700 mb-2">NIK</label>
                    <input type="text" id="nik" name="nik" placeholder="Masukkan NIK karyawan" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <p class="text-xs text-gray-400 mt-2">NIK akan divalidasi melalui authentication API perusahaan.</p>
                </div>
                <div class="mb-5">
                    <label for="role_company" class="block text-sm font-medium text-gray-700 mb-2">Role Aplikasi</label>
                    <select id="role_company" name="role" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="viewer">Viewer</option>
                        <option value="admin">Admin</option>
                    </select>
                    <p class="text-xs text-gray-400 mt-2">Role ditentukan oleh Administrator aplikasi.</p>
                </div>
            </div>
            <div id="externalFields" class="hidden">
                <div class="mb-5">
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nama</label>
                    <input type="text" id="name" name="name" placeholder="Nama auditor" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div class="mb-5">
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                    <input type="email" id="email" name="email" placeholder="email@auditor.com" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div class="mb-5">
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                    <input type="password" id="password" name="password" placeholder="Minimal 8 karakter" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div class="flex items-start gap-3 bg-blue-50 border border-blue-100 text-blue-700 rounded-lg p-4 mb-2">
                    <svg class="w-5 h-5 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M12 20a8 8 0 100-16 8 8 0 000 16z" />
                    </svg>
                    <div class="text-sm">
                        <p class="font-medium">External Auditor</p>
                        <p class="text-blue-600 mt-1">User external otomatis mendapatkan role Viewer.</p>
                    </div>
                </div>
            </div>
            <div class="flex justify-end gap-3 mt-7 pt-5 border-t border-gray-100">
                <button type="button" onclick="closeModal()" class="px-4 py-2.5 border border-gray-300 text-gray-600 rounded-lg text-sm font-medium hover:bg-gray-50">
                    Batal
                </button>
                <button type="submit" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-medium transition">
                    Simpan User
                </button>
            </div>
        </form>
    </div>
</div>