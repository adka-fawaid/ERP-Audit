@extends('layouts.app')

@section('title', 'Matriks Akses')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Matriks Akses</h1>
        <p class="mt-1 text-sm text-gray-500">Monitoring hubungan akses user terhadap program ERP QAD.</p>
    </div>

    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-3">
        <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium uppercase tracking-wide text-gray-400">Total User</p>
                    <p class="mt-2 text-2xl font-bold text-gray-900">0</p>
                </div>
                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-blue-50 text-blue-600">
                    <i class="fa-solid fa-users text-lg"></i>
                </div>
            </div>
        </div>

        <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium uppercase tracking-wide text-gray-400">Total Program</p>
                    <p class="mt-2 text-2xl font-bold text-gray-900">0</p>
                </div>
                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-violet-50 text-violet-600">
                    <i class="fa-solid fa-table-cells-large text-lg"></i>
                </div>
            </div>
        </div>

        <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium uppercase tracking-wide text-gray-400">Total Akses</p>
                    <p class="mt-2 text-2xl font-bold text-gray-900">0</p>
                </div>
                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600">
                    <i class="fa-solid fa-link text-lg"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="rounded-xl border border-gray-200 bg-white shadow-sm">
        <div class="flex items-center justify-between border-b border-gray-100 px-6 py-5">
            <div>
                <h2 class="font-semibold text-gray-900">Matriks User × Program</h2>
                <p class="mt-1 text-xs text-gray-400">Frekuensi penggunaan program oleh masing-masing user.</p>
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
                        <th class="sticky left-0 bg-gray-50 px-6 py-4 text-left font-semibold text-gray-500">User</th>
                        <th class="px-6 py-4 text-center font-semibold text-gray-500">Program 1</th>
                        <th class="px-6 py-4 text-center font-semibold text-gray-500">Program 2</th>
                        <th class="px-6 py-4 text-center font-semibold text-gray-500">Program 3</th>
                        <th class="px-6 py-4 text-center font-semibold text-gray-500">Program 4</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100">
                    <tr>
                        <td colspan="5" class="px-6 py-14 text-center">
                            <div class="flex flex-col items-center text-gray-400">
                                <i class="fa-solid fa-table-cells-large mb-3 text-3xl text-violet-200"></i>
                                <p class="text-sm font-medium text-gray-500">Belum ada data matriks</p>
                                <p class="mt-1 text-xs text-gray-400">Data akses user terhadap program akan ditampilkan di sini.</p>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection