<div id="editUserModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black bg-opacity-50 p-4">
    <div class="w-full max-w-lg overflow-y-auto rounded-2xl bg-white shadow-2xl">
        <div class="flex items-center justify-between border-b border-gray-100 px-6 py-5">
            <div>
                <h2 class="text-xl font-bold text-gray-900">Edit User</h2>
                <p class="mt-1 text-sm text-gray-400">Perbarui role dan status akses QAD.</p>
            </div>

            <button type="button" onclick="closeEditModal()" class="flex h-9 w-9 items-center justify-center rounded-lg text-2xl text-gray-400 hover:bg-gray-100 hover:text-gray-700">
                &times;
            </button>
        </div>

        <form id="editUserForm" method="POST" class="p-6">
            @csrf
            @method('PUT')
            <div class="mb-5">
                <label for="editNik" class="mb-2 block text-sm font-medium text-gray-700">NIK</label>
                <input type="text" id="editNik" readonly class="w-full rounded-lg border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm text-gray-500">
            </div>
            <div class="mb-5">
                <label for="editName" class="mb-2 block text-sm font-medium text-gray-700">Nama</label>
                <input type="text" id="editName" readonly class="w-full rounded-lg border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm text-gray-500">
            </div>
            <div class="mb-5">
                <label for="editRole" class="mb-2 block text-sm font-medium text-gray-700">Role</label>
                <select id="editRole" name="role" required class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="viewer">Viewer</option>
                    <option value="admin">Admin</option>
                </select>
            </div>
            <div>
                <label for="editStatus" class="mb-2 block text-sm font-medium text-gray-700">Status Akses QAD</label>
                <select id="editStatus" name="status" required class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="active">Aktif</option>
                    <option value="inactive">Nonaktif</option>
                </select>
                <p class="mt-2 text-xs text-gray-400">
                    Status ini hanya mengatur akses user ke QAD Audit.
                </p>
            </div>

            <div class="mt-7 flex justify-end gap-3 border-t border-gray-100 pt-5">
                <button type="button" onclick="closeEditModal()" class="rounded-lg border border-gray-300 px-4 py-2.5 text-sm font-medium text-gray-600 hover:bg-gray-50">
                    Batal
                </button>
                <button type="submit" class="rounded-lg bg-blue-600 px-5 py-2.5 text-sm font-medium text-white transition hover:bg-blue-700">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>