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
            <form id="activityLogFilters" method="GET" action="{{ route('logActivity.index') }}" class="flex flex-wrap items-center gap-2">
                <input type="search" name="search" value="{{ request('search') }}" placeholder="Cari aktivitas..." class="h-10 rounded-lg border border-gray-200 bg-gray-50 px-3 text-sm text-gray-600 outline-none transition focus:border-blue-400 focus:bg-white focus:ring-2 focus:ring-blue-100">
                <select name="activity" class="h-10 rounded-lg border border-gray-200 bg-gray-50 px-3 text-sm text-gray-600 outline-none transition focus:border-blue-400 focus:bg-white focus:ring-2 focus:ring-blue-100">
                    <option value="">Semua Aktivitas</option>
                    @foreach($activities as $value => $label)
                        <option value="{{ $value }}" {{ request('activity') == $value ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                <select name="user" class="h-10 rounded-lg border border-gray-200 bg-gray-50 px-3 text-sm text-gray-600 outline-none transition focus:border-blue-400 focus:bg-white focus:ring-2 focus:ring-blue-100">
                    <option value="">Semua User</option>
                    @foreach($users as $user)
                        <option value="{{ $user }}" {{ request('user') == $user ? 'selected' : '' }}>{{ $user }}</option>
                    @endforeach
                </select>
                <input type="date" name="start_date" value="{{ request('start_date') }}" class="h-10 rounded-lg border border-gray-200 bg-gray-50 px-3 text-sm text-gray-600 outline-none transition focus:border-blue-400 focus:bg-white focus:ring-2 focus:ring-blue-100">
                <input type="date" name="end_date" value="{{ request('end_date') }}" class="h-10 rounded-lg border border-gray-200 bg-gray-50 px-3 text-sm text-gray-600 outline-none transition focus:border-blue-400 focus:bg-white focus:ring-2 focus:ring-blue-100">
                <a id="activityLogReset" href="{{ route('logActivity.index') }}" class="h-10 rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm font-medium text-gray-600 transition hover:bg-gray-50 hover:text-gray-900 {{ request()->hasAny(['search', 'activity', 'user', 'start_date', 'end_date']) ? '' : 'hidden' }}">Reset</a>
            </form>
        </div>
    </div>
    <div class="rounded-2xl border border-gray-200 bg-white shadow-sm">
        <div class="flex items-center justify-between border-b border-gray-100 px-5 py-4">
            <div>
                <h2 class="font-semibold text-gray-900">Riwayat Aktivitas</h2>
                <p class="mt-1 text-xs text-gray-500">Catatan aktivitas pengguna pada aplikasi.</p>
            </div>

            <div class="flex items-center gap-3">
                <button type="button" id="activityLogExport" data-export-url="{{ route('logActivity.export') }}" data-export-report="Log Aktivitas" class="flex h-9 items-center gap-2 rounded-lg border border-gray-200 px-3 text-sm font-medium text-gray-600 transition hover:bg-gray-50">
                    <i class="fa-solid fa-file-excel text-green-600"></i>
                    Export Excel
                </button>
                <span id="activityLogTotal" class="rounded-full bg-blue-50 px-3 py-1 text-xs font-semibold text-blue-700">{{ number_format($logs->total()) }} Aktivitas</span>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full min-w-[900px]">
                <thead>
                    <tr class="border-b border-gray-100 bg-gray-50/70">
                        <th class="px-5 py-3 text-left text-[11px] font-semibold uppercase tracking-wide text-gray-500">Waktu</th>
                        <th class="px-5 py-3 text-left text-[11px] font-semibold uppercase tracking-wide text-gray-500">User</th>
                        <th class="px-5 py-3 text-left text-[11px] font-semibold uppercase tracking-wide text-gray-500">Role</th>
                        <th class="px-5 py-3 text-left text-[11px] font-semibold uppercase tracking-wide text-gray-500">Aktivitas</th>
                        <th class="px-5 py-3 text-left text-[11px] font-semibold uppercase tracking-wide text-gray-500">IP Address</th>
                        <th class="px-5 py-3 text-center text-[11px] font-semibold uppercase tracking-wide text-gray-500">Detail</th>
                    </tr>
                </thead>

                <tbody id="activityLogRows" class="divide-y divide-gray-100">
                    @include('logActivity.partials.rows', ['logs' => $logs, 'activities' => $activities])
                </tbody>
            </table>
        </div>

        <div class="flex items-center justify-between border-t border-gray-100 px-5 py-4">
            <p id="activityLogRange" class="text-xs text-gray-500">
                Menampilkan {{ $logs->firstItem() ?? 0 }}-{{ $logs->lastItem() ?? 0 }} dari {{ $logs->total() }} aktivitas
            </p>

            <div id="activityLogPagination">
                {{ $logs->links() }}
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
                <p class="text-[11px] font-medium uppercase tracking-wide text-gray-400">Role</p>
                <p id="detailRole" class="mt-1 text-sm text-gray-700">-</p>
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
