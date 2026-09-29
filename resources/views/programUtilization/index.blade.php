@extends('layouts.app')

@section('title', 'Utilisasi Program')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Utilisasi Program</h1>
        <p class="mt-1 text-sm text-gray-500">Monitoring penggunaan program ERP QAD berdasarkan aktivitas transaksi.</p>
    </div>

    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium uppercase tracking-wide text-gray-400">Total Program</p>
                    <p class="mt-2 text-2xl font-bold text-gray-900">0</p>
                </div>
                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-blue-50 text-blue-600">
                    <i class="fa-solid fa-window-maximize text-lg"></i>
                </div>
            </div>
        </div>

        <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium uppercase tracking-wide text-gray-400">Program Aktif</p>
                    <p class="mt-2 text-2xl font-bold text-gray-900">0</p>
                </div>
                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-green-50 text-green-600">
                    <i class="fa-solid fa-circle-check text-lg"></i>
                </div>
            </div>
        </div>

        <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium uppercase tracking-wide text-gray-400">Program Jarang</p>
                    <p class="mt-2 text-2xl font-bold text-gray-900">0</p>
                </div>
                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-amber-50 text-amber-600">
                    <i class="fa-solid fa-chart-simple text-lg"></i>
                </div>
            </div>
        </div>

        <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium uppercase tracking-wide text-gray-400">Total Transaksi</p>
                    <p class="mt-2 text-2xl font-bold text-gray-900">0</p>
                </div>
                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-violet-50 text-violet-600">
                    <i class="fa-solid fa-arrow-right-arrow-left text-lg"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="rounded-xl border border-gray-200 bg-white shadow-sm">
        <div class="flex items-center justify-between border-b border-gray-100 px-6 py-5">
            <div>
                <h2 class="font-semibold text-gray-900">Ranking Utilisasi Program</h2>
                <p class="mt-1 text-xs text-gray-400">Daftar penggunaan program ERP QAD.</p>
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
                        <th class="px-6 py-4 text-left font-semibold text-gray-500">No</th>
                        <th class="px-6 py-4 text-left font-semibold text-gray-500">Program</th>
                        <th class="px-6 py-4 text-left font-semibold text-gray-500">Total Transaksi</th>
                        <th class="px-6 py-4 text-left font-semibold text-gray-500">ISS-SO</th>
                        <th class="px-6 py-4 text-left font-semibold text-gray-500">RCT-PO</th>
                        <th class="px-6 py-4 text-left font-semibold text-gray-500">ISS-WO</th>
                        <th class="px-6 py-4 text-left font-semibold text-gray-500">Status</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100">
                    <tr>
                        <td colspan="7" class="px-6 py-14 text-center">
                            <div class="flex flex-col items-center text-gray-400">
                                <i class="fa-solid fa-window-maximize mb-3 text-3xl text-blue-200"></i>
                                <p class="text-sm font-medium text-gray-500">Belum ada data program</p>
                                <p class="mt-1 text-xs text-gray-400">Data utilisasi akan ditampilkan di sini.</p>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection