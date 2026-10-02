@extends('layouts.app')

@section('title', 'Log Aktivitas')

@section('content')
<div class="space-y-6">

    <div>
        <h1 class="text-xl font-bold text-gray-900">Log Aktivitas</h1>
        <p class="mt-1 text-sm text-gray-500">Riwayat aktivitas pengguna dalam sistem QAD Audit.</p>
    </div>

    <div class="rounded-2xl border border-gray-200 bg-white shadow-sm">
        <div class="flex flex-col gap-4 p-5 lg:flex-row lg:items-center lg:justify-between">

            <div class="flex flex-wrap items-center gap-2">

                <select id="activityFilter" class="h-10 rounded-lg border border-gray-200 bg-gray-50 px-3 text-sm text-gray-600 outline-none transition focus:border-blue-400 focus:bg-white focus:ring-2 focus:ring-blue-100">
                    <option value="">Semua Aktivitas</option>
                    <option value="LOGIN">Login</option>
                    <option value="LOGOUT">Logout</option>
                    <option value="VIEW_DASHBOARD">View Dashboard</option>
                    <option value="VIEW_PROGRAM">View Utilisasi Program</option>
                    <option value="VIEW_ANOMALY">View Log Anomali</option>
                    <option value="VIEW_ACCESS_MATRIX">View Matriks Akses</option>
                    <option value="EXPORT_REPORT">Export Report</option>
                    <option value="VIEW_USER">View User</option>
                    <option value="CREATE_USER">Create User</option>
                    <option value="UPDATE_USER">Update User</option>
                    <option value="DELETE_USER">Delete User</option>
                    <option value="CHANGE_ROLE">Change Role</option>
                </select>

                <select id="userFilter" class="h-10 rounded-lg border border-gray-200 bg-gray-50 px-3 text-sm text-gray-600 outline-none transition focus:border-blue-400 focus:bg-white focus:ring-2 focus:ring-blue-100">
                    <option value="">Semua Tipe</option>
                </select>

                <input type="date" id="startDate" class="h-10 rounded-lg border border-gray-200 bg-gray-50 px-3 text-sm text-gray-600 outline-none transition focus:border-blue-400 focus:bg-white focus:ring-2 focus:ring-blue-100">

                <input type="date" id="endDate" class="h-10 rounded-lg border border-gray-200 bg-gray-50 px-3 text-sm text-gray-600 outline-none transition focus:border-blue-400 focus:bg-white focus:ring-2 focus:ring-blue-100">

                <button type="button" id="resetActivityFilter" class="hidden h-10 rounded-lg border border-gray-200 bg-white px-3 text-sm font-medium text-gray-600 transition hover:bg-gray-50 hover:text-gray-900">
                    Reset
                </button>

            </div>

        </div>
    </div>

    <div class="rounded-2xl border border-gray-200 bg-white shadow-sm">

        <div class="flex items-center justify-between border-b border-gray-100 px-5 py-4">
            <div>
                <h2 class="font-semibold text-gray-900">Riwayat Aktivitas</h2>
                <p class="mt-1 text-xs text-gray-500">Catatan aktivitas pengguna pada aplikasi.</p>
            </div>

            <span class="rounded-full bg-blue-50 px-3 py-1 text-xs font-semibold text-blue-700">0 Aktivitas</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full min-w-[900px]">
                <thead>
                    <tr class="border-b border-gray-100 bg-gray-50/70">
                        <th class="px-5 py-3 text-left text-[11px] font-semibold uppercase tracking-wide text-gray-500">Waktu</th>
                        <th class="px-5 py-3 text-left text-[11px] font-semibold uppercase tracking-wide text-gray-500">User</th>
                        <th class="px-5 py-3 text-left text-[11px] font-semibold uppercase tracking-wide text-gray-500">Aktivitas</th>
                        <th class="px-5 py-3 text-left text-[11px] font-semibold uppercase tracking-wide text-gray-500">IP Address</th>
                        <th class="px-5 py-3 text-center text-[11px] font-semibold uppercase tracking-wide text-gray-500">Detail</th>
                    </tr>
                </thead>

                <tbody id="activityTableBody">
                    <tr>
                        <td colspan="5" class="px-5 py-16 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <div class="flex h-12 w-12 items-center justify-center rounded-full bg-gray-100">
                                    <i class="fa-solid fa-clipboard-list text-lg text-gray-400"></i>
                                </div>
                                <p class="mt-3 text-sm font-semibold text-gray-700">Belum ada aktivitas</p>
                                <p class="mt-1 text-xs text-gray-400">Log aktivitas akan muncul di halaman ini.</p>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="flex items-center justify-between border-t border-gray-100 px-5 py-4">
            <p class="text-xs text-gray-500">Menampilkan 0 aktivitas</p>

            <div class="flex items-center gap-1">
                <button type="button" disabled class="flex h-8 w-8 items-center justify-center rounded-lg border border-gray-200 text-gray-300"><i class="fa-solid fa-chevron-left text-[10px]"></i></button>

                <button type="button" disabled class="flex h-8 w-8 items-center justify-center rounded-lg border border-gray-200 text-gray-300"><i class="fa-solid fa-chevron-right text-[10px]"></i></button>
            </div>
        </div>

    </div>

</div>

<div id="activityDetailModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-gray-900/40 p-4 backdrop-blur-sm">
    <div class="w-full max-w-md overflow-hidden rounded-2xl bg-white shadow-2xl">

        <div class="flex items-center justify-between border-b border-gray-100 px-5 py-4">
            <div>
                <h2 class="font-semibold text-gray-900">Detail Aktivitas</h2>
                <p class="mt-0.5 text-xs text-gray-400">Informasi aktivitas pengguna.</p>
            </div>

            <button type="button" onclick="closeActivityDetail()" class="flex h-8 w-8 items-center justify-center rounded-lg text-gray-400 transition hover:bg-gray-100 hover:text-gray-700">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <div class="space-y-4 p-5">

            <div>
                <p class="text-[11px] font-medium uppercase tracking-wide text-gray-400">Aktivitas</p>
                <p id="detailActivity" class="mt-1 text-sm font-semibold text-gray-900">-</p>
            </div>

            <div>
                <p class="text-[11px] font-medium uppercase tracking-wide text-gray-400">User</p>
                <p id="detailUser" class="mt-1 text-sm font-semibold text-gray-900">-</p>
            </div>

            <div>
                <p class="text-[11px] font-medium uppercase tracking-wide text-gray-400">Waktu</p>
                <p id="detailTime" class="mt-1 text-sm text-gray-700">-</p>
            </div>

            <div>
                <p class="text-[11px] font-medium uppercase tracking-wide text-gray-400">IP Address</p>
                <p id="detailIp" class="mt-1 text-sm text-gray-700">-</p>
            </div>

            <div>
                <p class="text-[11px] font-medium uppercase tracking-wide text-gray-400">Browser</p>
                <p id="detailBrowser" class="mt-1 text-sm text-gray-700">-</p>
            </div>

            <div>
                <p class="text-[11px] font-medium uppercase tracking-wide text-gray-400">Deskripsi</p>
                <p id="detailDescription" class="mt-1 text-sm leading-6 text-gray-700">-</p>
            </div>

        </div>

        <div class="border-t border-gray-100 px-5 py-4">
            <button type="button" onclick="closeActivityDetail()" class="w-full rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm font-medium text-gray-600 transition hover:bg-gray-50 hover:text-gray-900">
                Tutup
            </button>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
function closeActivityDetail() {
    const modal = document.getElementById('activityDetailModal');

    if (!modal) return;

    modal.classList.remove('flex');
    modal.classList.add('hidden');
    document.body.classList.remove('overflow-hidden');
}

document.addEventListener('DOMContentLoaded', () => {
    const modal = document.getElementById('activityDetailModal');

    modal?.addEventListener('click', event => {
        if (event.target === modal) {
            closeActivityDetail();
        }
    });

    document.addEventListener('keydown', event => {
        if (event.key === 'Escape') {
            closeActivityDetail();
        }
    });
});
</script>
@endpush