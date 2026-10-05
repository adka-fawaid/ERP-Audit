@extends('layouts.app')
@section('title', 'Utilisasi Program')
@section('content')
<div class="space-y-6">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Utilisasi Program</h1>
            <p class="mt-1 text-sm text-gray-500">Monitoring penggunaan program ERP QAD berdasarkan aktivitas transaksi.</p>
        </div>
            <form id="periodFilter" method="GET" action="{{ request()->url() }}" class="flex gap-3">
                <select name="month" id="month" class="h-10 min-w-[160px] rounded-lg border border-gray-200 bg-gray-100 px-3 text-sm text-gray-700 focus:border-blue-500 focus:ring-blue-500">
                    <option value="all" {{ $month == 'all' ? 'selected' : '' }}>Semua Bulan</option>

                    @foreach(range(1, 12) as $monthNumber)
                        <option value="{{ $monthNumber }}" {{ (string) $month === (string) $monthNumber ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::create()->month($monthNumber)->translatedFormat('F') }}
                        </option>
                    @endforeach
                </select>

                <select name="year" id="year" class="h-10 min-w-[140px] rounded-lg border border-gray-200 bg-gray-100 px-3 text-sm text-gray-700 focus:border-blue-500 focus:ring-blue-500">
                    <option value="all" {{ $year == 'all' ? 'selected' : '' }}>Semua Tahun</option>

                    @foreach($years as $yearOption)
                        <option value="{{ $yearOption }}" {{ (string) $year === (string) $yearOption ? 'selected' : '' }}>
                            {{ $yearOption }}
                        </option>
                    @endforeach
                </select>

                <input type="hidden" name="search" value="{{ $search }}">
                <input type="hidden" name="status" value="{{ $status }}">
            </form>
    </div>
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <a href="{{ request()->url() . '?' . http_build_query(['month' => $month, 'year' => $year]) }}" class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium uppercase tracking-wide text-gray-400">Total Program</p>
                    <p class="mt-2 text-2xl font-bold text-gray-900">{{ number_format($totalPrograms) }}</p>
                </div>

                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-blue-50 text-blue-600">
                    <i class="fa-solid fa-window-maximize text-lg"></i>
                </div>
            </div>
        </a>
        <a href="{{ request()->url() . '?' . http_build_query(['month' => $month, 'year' => $year, 'status' => 'active']) }}" class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium uppercase tracking-wide text-gray-400">Program Aktif</p>
                    <p class="mt-2 text-2xl font-bold text-gray-900">{{ number_format($activePrograms) }}</p>
                </div>

                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-green-50 text-green-600">
                    <i class="fa-solid fa-circle-check text-lg"></i>
                </div>
            </div>
        </a>
        <a href="{{ request()->url() . '?' . http_build_query(['month' => $month, 'year' => $year, 'status' => 'rare']) }}" class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium uppercase tracking-wide text-gray-400">Program Jarang</p>
                    <p class="mt-2 text-2xl font-bold text-gray-900">{{ number_format($rarePrograms) }}</p>
                </div>

                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-amber-50 text-amber-600">
                    <i class="fa-solid fa-chart-simple text-lg"></i>
                </div>
            </div>
        </a>
        <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium uppercase tracking-wide text-gray-400">Total Transaksi</p>
                    <p class="mt-2 text-2xl font-bold text-gray-900">{{ number_format($totalTransactions) }}</p>
                </div>

                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-violet-50 text-violet-600">
                    <i class="fa-solid fa-arrow-right-arrow-left text-lg"></i>
                </div>
            </div>
        </div>

    </div>
    <div class="rounded-xl border border-gray-200 bg-white shadow-sm">
        <div class="flex flex-col gap-4 border-b border-gray-100 px-6 py-5 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <h2 class="font-semibold text-gray-900">Ranking Utilisasi Program</h2>
                <p class="mt-1 text-xs text-gray-400">Daftar penggunaan program ERP QAD berdasarkan jumlah transaksi.</p>
            </div>
            <button type="button" data-export-url="{{ route('programUtilization.export') }}" data-export-report="Utilisasi Program" class="flex h-9 items-center justify-center gap-2 rounded-lg border border-gray-200 px-3 text-sm font-medium text-gray-600 transition hover:bg-gray-50">
                <i class="fa-solid fa-file-excel text-green-600"></i>
                Export Excel
            </button>
        </div>
        <div class="flex flex-col gap-3 border-b border-gray-100 px-6 py-4 sm:flex-row sm:items-center">
            <div class="relative flex-1 sm:max-w-md">
                <div class="flex h-10 items-center rounded-lg border border-gray-200 bg-white focus-within:border-blue-500 focus-within:ring-1 focus-within:ring-blue-500">
                    <div class="flex w-10 shrink-0 items-center justify-center text-gray-400">
                        <i class="fa-solid fa-magnifying-glass text-sm"></i>
                    </div>
                    <input type="text" id="programSearch" value="{{ $search }}" placeholder="Cari nama program..." autocomplete="off" class="h-full min-w-0 flex-1 border-0 bg-transparent px-0 pr-3 text-sm text-gray-700 outline-none placeholder:text-gray-400 focus:ring-0">
                </div>
            </div>
            <select id="statusFilter" class="h-10 rounded-lg border border-gray-200 bg-white px-3 text-sm text-gray-700 focus:border-blue-500 focus:ring-blue-500">
                <option value="all" {{ $status === 'all' ? 'selected' : '' }}>Semua Status</option>
                <option value="active" {{ $status === 'active' ? 'selected' : '' }}>Aktif</option>
                <option value="rare" {{ $status === 'rare' ? 'selected' : '' }}>Jarang</option>
            </select>
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
                    @forelse($programs as $index => $program)
                        <tr class="transition hover:bg-gray-50">
                            <td class="whitespace-nowrap px-6 py-4 text-gray-500">{{ $programs->firstItem() + $index }}</td>
                            <td class="whitespace-nowrap px-6 py-4 font-medium text-gray-900">{{ $program->program }}</td>
                            <td class="whitespace-nowrap px-6 py-4 font-semibold text-gray-700">{{ number_format($program->total) }}</td>
                            <td class="whitespace-nowrap px-6 py-4 text-gray-600">{{ number_format($program->iss_so) }}</td>
                            <td class="whitespace-nowrap px-6 py-4 text-gray-600">{{ number_format($program->rct_po) }}</td>
                            <td class="whitespace-nowrap px-6 py-4 text-gray-600">{{ number_format($program->iss_wo) }}</td>
                            <td class="whitespace-nowrap px-6 py-4">
                                @if($program->total >= 50)
                                    <span class="inline-flex items-center gap-1.5 rounded-full bg-green-50 px-2.5 py-1 text-xs font-medium text-green-700">
                                        <span class="h-2 w-2 rounded-full bg-green-500"></span>
                                        Aktif
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 rounded-full bg-amber-50 px-2.5 py-1 text-xs font-medium text-amber-700">
                                        <span class="h-2 w-2 rounded-full bg-amber-500"></span>
                                        Jarang
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-14 text-center">
                                <div class="flex flex-col items-center text-gray-400">
                                    <i class="fa-solid fa-window-maximize mb-3 text-3xl text-blue-200"></i>
                                    <p class="text-sm font-medium text-gray-500">
                                        Tidak ada data program
                                    </p>
                                    <p class="mt-1 text-xs text-gray-400">
                                        Coba ubah pencarian atau filter yang digunakan.
                                    </p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($programs->hasPages())
            <div class="border-t border-gray-100 px-6 py-4">
                {{ $programs->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
