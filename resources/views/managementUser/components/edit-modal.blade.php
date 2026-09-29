<div id="editUserModal" class="fixed inset-0 z-50 bg-black bg-opacity-50 hidden items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between px-6 py-5 border-b border-gray-100">
            <div>
                <h2 class="text-xl font-bold text-gray-900">Edit User</h2>
                <p class="text-sm text-gray-400 mt-1">Perbarui data dan hak akses user.</p>
            </div>
            <button type="button" onclick="closeEditModal()" class="w-9 h-9 rounded-lg text-gray-400 hover:bg-gray-100 hover:text-gray-700 flex items-center justify-center text-2xl">
                &times;
            </button>
        </div>
        <form id="editUserForm" method="POST" class="p-6">
            @csrf
            @method('PUT')
            <div id="editCompanyFields">
                <div class="mb-5">
                    <label for="editNik" class="block text-sm font-medium text-gray-700 mb-2">NIK</label>
                    <input type="text" id="editNik" name="nik" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
            </div>
            <div id="editExternalFields">
                <div class="mb-5">
                    <label for="editName" class="block text-sm font-medium text-gray-700 mb-2">Nama</label>
                    <input type="text" id="editName" name="name" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div class="mb-5">
                    <label for="editEmail" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                    <input type="email" id="editEmail" name="email" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div class="mb-5">
                    <label for="editPassword" class="block text-sm font-medium text-gray-700 mb-2">Password Baru</label>
                    <input type="password" id="editPassword" name="password" placeholder="Kosongkan jika tidak diubah" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Role</label>
                <select id="editRole" class="w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm text-gray-700 focus:border-blue-500 focus:ring-2 focus:ring-blue-500">
                    <option value="viewer">Viewer</option>
                    <option value="admin">Admin</option>
                </select>
                <div id="editRoleDisplay" class="hidden w-full rounded-lg border border-gray-200 bg-gray-50 px-4 py-3 text-sm text-gray-500">
                    Viewer
                </div>
                <input type="hidden" id="editRoleHidden" name="role" value="viewer">
            </div>
            <div class="mb-5">
                <label for="editStatus" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select id="editStatus" name="is_active" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="1">Aktif</option>
                    <option value="0">Nonaktif</option>
                </select>
            </div>
            <div class="flex justify-end gap-3 mt-7 pt-5 border-t border-gray-100">
                <button type="button" onclick="closeEditModal()" class="px-4 py-2.5 border border-gray-300 text-gray-600 rounded-lg text-sm font-medium hover:bg-gray-50">
                    Batal
                </button>
                <button type="submit" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-medium transition">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>