@extends('layouts.app')

@section('title', 'Log Anomali')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Log Anomali</h1>
        <p class="mt-1 text-sm text-gray-500">Monitoring aktivitas transaksi yang terindikasi sebagai anomali.</p>
    </div>

    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-3">
        <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium uppercase tracking-wide text-gray-400">Total Anomali</p>
                    <p class="mt-2 text-2xl font-bold text-gray-900">0</p>
                </div>
                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-red-50 text-red-500">
                    <i class="fa-solid fa-triangle-exclamation text-lg"></i>
                </div>
            </div>
        </div>

        <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium uppercase tracking-wide text-gray-400">User Terlibat</p>
                    <p class="mt-2 text-2xl font-bold text-gray-900">0</p>
                </div>
                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-orange-50 text-orange-500">
                    <i class="fa-solid fa-users text-lg"></i>
                </div>
            </div>
        </div>

        <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium uppercase tracking-wide text-gray-400">Aktivitas Minggu</p>
                    <p class="mt-2 text-2xl font-bold text-gray-900">0</p>
                </div>
                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-amber-50 text-amber-600">
                    <i class="fa-solid fa-calendar-xmark text-lg"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="rounded-xl border border-gray-200 bg-white shadow-sm">
        <div class="flex items-center justify-between border-b border-gray-100 px-6 py-5">
            <div>
                <h2 class="font-semibold text-gray-900">Daftar Log Anomali</h2>
                <p class="mt-1 text-xs text-gray-400">Seluruh aktivitas yang teridentifikasi sebagai anomali.</p>
            </div>

            <button type="button" class="flex h-9 items-center gap-2 rounded-lg border border-gray-200 px-3 text-sm font-medium text-gray-600 transition hover:bg-gray-50">
                <i class="fa-solid fa-file-excel text-green-600"></i>
                Export Excel
            </button>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="border-b border-gray-100 bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-left font-semibold text-gray-500">Tanggal</th>
                        <th class="px-6 py-4 text-left font-semibold text-gray-500">Waktu</th>
                        <th class="px-6 py-4 text-left font-semibold text-gray-500">User</th>
                        <th class="px-6 py-4 text-left font-semibold text-gray-500">Program</th>
                        <th class="px-6 py-4 text-left font-semibold text-gray-500">Transaksi</th>
                        <th class="px-6 py-4 text-left font-semibold text-gray-500">Lokasi</th>
                        <th class="px-6 py-4 text-left font-semibold text-gray-500">Status</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100">
                    <tr>
                        <td colspan="7" class="px-6 py-14 text-center">
                            <div class="flex flex-col items-center text-gray-400">
                                <i class="fa-solid fa-shield-halved mb-3 text-3xl text-red-200"></i>
                                <p class="text-sm font-medium text-gray-500">Belum ada log anomali</p>
                                <p class="mt-1 text-xs text-gray-400">Aktivitas anomali akan ditampilkan di sini.</p>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection