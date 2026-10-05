<div id="viewUserModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-gray-900/40 p-4 backdrop-blur-sm">
    <div class="w-full max-w-md overflow-hidden rounded-2xl bg-white shadow-2xl">
        <div class="flex items-center justify-between border-b border-gray-100 px-6 py-5">
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-blue-600">User Detail</p>
                <h2 class="mt-1 text-xl font-bold text-gray-900">Informasi User</h2>
            </div>

            <button type="button" onclick="closeViewModal()" class="flex h-9 w-9 items-center justify-center rounded-lg text-gray-400 transition hover:bg-gray-100 hover:text-gray-700">
                &times;
            </button>
        </div>

        <div class="p-6">
            <div class="mb-6 flex items-center gap-4">
                <div id="viewAvatar" class="flex h-12 w-12 items-center justify-center rounded-xl bg-blue-50 font-semibold text-blue-600">
                    -
                </div>

                <div class="min-w-0">
                    <p id="viewName" class="truncate text-base font-semibold text-gray-900">-</p>
                    <p id="viewNik" class="truncate text-sm text-gray-400">-</p>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div class="rounded-xl bg-gray-50 p-4">
                    <p class="text-xs text-gray-400">NIK</p>
                    <p id="viewNikDetail" class="mt-1 text-sm font-medium text-gray-800">-</p>
                </div>

                <div class="rounded-xl bg-gray-50 p-4">
                    <p class="text-xs text-gray-400">Role</p>
                    <p id="viewRole" class="mt-1 text-sm font-medium text-gray-800">-</p>
                </div>

                <div class="col-span-2 rounded-xl bg-gray-50 p-4">
                    <p class="text-xs text-gray-400">Status Akses QAD</p>
                    <p id="viewStatus" class="mt-1 text-sm font-medium text-gray-800">-</p>
                </div>
            </div>
        </div>
    </div>
</div>