<div id="deleteUserModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-gray-900/40 p-4 backdrop-blur-sm">
    <div class="w-full max-w-sm overflow-hidden rounded-2xl bg-white shadow-2xl">
        <div class="p-6 text-center">
            <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-red-50 text-red-500">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 9v4m0 4h.01M10.29 3.86L2.82 17a2 2 0 001.74 3h14.88a2 2 0 001.74-3L13.71 3.86a2 2 0 00-3.42 0z" />
                </svg>
            </div>
            <h2 class="mt-4 text-lg font-bold text-gray-900">Hapus User?</h2>
            <p class="mt-2 text-sm leading-6 text-gray-500">
                Anda akan menghapus user <span id="deleteUserName" class="font-semibold text-gray-700">-</span>.
                Tindakan ini tidak dapat dibatalkan.
            </p>
            <div class="mt-6 flex gap-3">
                <button type="button" onclick="closeDeleteModal()" class="flex-1 rounded-lg border border-gray-200 px-4 py-2.5 text-sm font-medium text-gray-600 transition hover:bg-gray-50">Batal</button>
                <form id="deleteUserForm" method="POST" class="flex-1">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full rounded-lg bg-red-500 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-red-600">Hapus User</button>
                </form>
            </div>
        </div>
    </div>
</div>