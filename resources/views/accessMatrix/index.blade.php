@extends('layouts.app')
@section('title', 'Matriks Akses')
@section('content')
<div id="matrixPage" class="space-y-6">
    <div class="flex items-end justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Matriks Akses</h1>
            <p class="mt-1 text-sm text-gray-500">Monitoring hubungan akses user terhadap program ERP QAD.</p>
        </div>
            <div class="flex gap-3">
                <select id="monthFilter"
                        class="h-10 min-w-[160px] rounded-lg border border-gray-200 bg-gray-100 px-3 text-sm text-gray-700 focus:border-blue-500 focus:ring-blue-500">
                    <option value="all" {{ $month == 'all' ? 'selected' : '' }}>Semua Bulan</option>
                    @foreach(range(1, 12) as $m)
                        <option value="{{ $m }}" {{ (string) $month === (string) $m ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                        </option>
                    @endforeach
                </select>
                <select id="yearFilter"
                        class="h-10 min-w-[140px] rounded-lg border border-gray-200 bg-gray-100 px-3 text-sm text-gray-700 focus:border-blue-500 focus:ring-blue-500">
                    <option value="all" {{ $year == 'all' ? 'selected' : '' }}>Semua Tahun</option>
                    @foreach($years as $y)
                        <option value="{{ $y }}" {{ (string) $year === (string) $y ? 'selected' : '' }}>
                            {{ $y }}
                        </option>
                    @endforeach
                </select>

</div>
    </div>
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
        <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium uppercase tracking-wide text-gray-400">Total User</p>
                    <p class="mt-2 text-2xl font-bold text-gray-900">{{ number_format($totalUsers) }}</p>
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
                    <p class="mt-2 text-2xl font-bold text-gray-900">{{ number_format($totalPrograms) }}</p>
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
                    <p class="mt-2 text-2xl font-bold text-gray-900">{{ number_format($totalAccess) }}</p>
                </div>
                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600">
                    <i class="fa-solid fa-link text-lg"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="rounded-xl border border-gray-200 bg-white shadow-sm">
        <div class="border-b border-gray-100 px-6 py-5">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <h2 class="font-semibold text-gray-900">Matriks User × Program</h2>
                    <p class="mt-1 text-xs text-gray-400">Jumlah transaksi berdasarkan user dan program.</p>
                </div>
                <button type="button" data-export-url="{{ route('accessMatrix.export') }}" data-export-report="Matriks Akses" class="flex h-9 items-center gap-2 rounded-lg border border-gray-200 px-3 text-sm font-medium text-gray-600 transition hover:bg-gray-50">
                    <i class="fa-solid fa-file-excel text-green-600"></i>
                    Export Excel
                </button>
            </div>
            <div class="mt-4 grid grid-cols-1 gap-3 md:grid-cols-2">
                <div class="relative">
                    <i class="fa-solid fa-user absolute left-3 top-1/2 -translate-y-1/2 text-xs text-gray-400"></i>
                    <input id="userSearch" type="text" value="{{ $userSearch }}" placeholder="Cari user..." class="w-full rounded-lg border border-gray-200 py-2.5 pl-9 pr-3 text-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
                <div class="relative">
                    <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-xs text-gray-400"></i>
                    <input id="programSearch" type="text" value="{{ $programSearch }}" placeholder="Cari program..." class="w-full rounded-lg border border-gray-200 py-2.5 pl-9 pr-3 text-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full min-w-max text-sm">
                <thead class="border-b border-gray-100 bg-gray-50">
                    <tr>
                        <th class="sticky left-0 z-10 bg-gray-50 px-6 py-4 text-left font-semibold text-gray-500">User</th>
                        @foreach($programs as $program)
                            <th class="min-w-[130px] px-5 py-4 text-center font-semibold text-gray-500">{{ $program }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($users as $user)
                        <tr class="hover:bg-gray-50">
                            <td class="sticky left-0 z-10 bg-white px-6 py-3 font-medium text-gray-700">{{ $user->user }}</td>
                            @foreach($programs as $program)
                                @php
                                    $value = optional($matrix->get($user->user)?->firstWhere('program', $program))->total ?? 0;
                                    $opacity = $maxMatrixValue > 0 ? max(0.08, min(0.8, $value / $maxMatrixValue)) : 0;
                                @endphp
                                <td class="px-5 py-3 text-center">
                                    <span class="inline-flex min-w-[45px] justify-center rounded-md px-2 py-1 text-xs font-semibold" style="{{ $value > 0 ? 'background-color: rgba(99, 102, 241, ' . $opacity . '); color: #3730a3;' : 'background-color: #f3f4f6; color: #9ca3af;' }}">{{ number_format($value) }}</span>
                                </td>
                            @endforeach
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ count($programs) + 1 }}" class="px-6 py-14 text-center">
                                <div class="flex flex-col items-center text-gray-400">
                                    <i class="fa-solid fa-table-cells-large mb-3 text-3xl text-violet-200"></i>
                                    <p class="text-sm font-medium text-gray-500">Data tidak ditemukan</p>
                                    <p class="mt-1 text-xs text-gray-400">Coba ubah filter atau kata pencarian.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="rounded-xl border border-gray-200 bg-white shadow-sm">
        <div class="border-b border-gray-100 px-6 py-5">
            <h2 class="font-semibold text-gray-900">Top 10 User</h2>
            <p class="mt-1 text-xs text-gray-400">User dengan jumlah transaksi terbanyak.</p>
        </div>
        <div class="h-[360px] p-5">
            <canvas id="topUsersChart"></canvas>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
    window.matrixData = {
        topUsers: @json($topUsers)
    };

    if (typeof window.initMatrixCharts === 'function') {
        window.initMatrixCharts();
    }
</script>
@endpush
