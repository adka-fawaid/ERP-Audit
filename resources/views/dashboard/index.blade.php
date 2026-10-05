@extends('layouts.app')
@section('title', 'Dashboard Utama - QAD Audit')
@section('page-title', 'Dashboard Utama')
@section('content')
<div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Dashboard Utama</h1>
        <p class="text-sm text-gray-500 mt-1">Monitoring dan audit utilisasi ERP QAD</p>
    </div>
    <div class="flex gap-3">
        <form method="GET" action="{{ route('dashboard') }}" class="flex gap-3">
            <select name="month"
                    onchange="this.form.submit()"
                    class="h-10 min-w-[160px] rounded-lg border border-gray-200 bg-gray-100 px-3 text-sm text-gray-700 focus:border-blue-500 focus:ring-blue-500">
                <option value="all" {{ $month == 'all' ? 'selected' : '' }}>Semua Bulan</option>
                @foreach(range(1, 12) as $m)
                    <option value="{{ $m }}" {{ (string) $month === (string) $m ? 'selected' : '' }}>
                        {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                    </option>
                @endforeach
            </select>
            <select name="year"
                    onchange="this.form.submit()"
                    class="h-10 min-w-[140px] rounded-lg border border-gray-200 bg-gray-100 px-3 text-sm text-gray-700 focus:border-blue-500 focus:ring-blue-500">
                <option value="all" {{ $year == 'all' ? 'selected' : '' }}>Semua Tahun</option>
                @foreach($years as $y)
                    <option value="{{ $y }}" {{ (string) $year === (string) $y ? 'selected' : '' }}>
                        {{ $y }}
                    </option>
                @endforeach
            </select>
        </form>
        <button type="button" data-export-url="{{ route('dashboard.export') }}" data-export-report="Dashboard" class="flex h-10 items-center gap-2 rounded-lg border border-gray-200 bg-white px-3 text-sm font-medium text-gray-600 transition hover:bg-gray-50">
            <i class="fa-solid fa-file-excel text-green-600"></i>
            Export Excel
        </button>
    </div>
</div>

<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5 mb-6">

    <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm">
        <div class="flex items-start justify-between">
            <div>
                <p class="text-sm text-gray-500">Total Transaksi</p>
                <h3 class="text-3xl font-bold text-gray-900 mt-2">{{ number_format($totalTransactions) }}</h3>
            </div>

            <div class="w-11 h-11 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3v18h18M7 16v-5m5 5V7m5 9v-8" />
                </svg>
            </div>
        </div>

        <p class="text-xs text-green-600 mt-3">Periode {{ $month === 'all' && $year === 'all' ? 'Semua Periode' : ($month === 'all' ? 'Semua Bulan ' . $year : ($year === 'all' ? \Carbon\Carbon::create()->month($month)->translatedFormat('F') . ' Semua Tahun' : \Carbon\Carbon::create($year, $month)->translatedFormat('F Y'))) }}</p>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm">
        <div class="flex items-start justify-between">
            <div>
                <p class="text-sm text-gray-500">Total User Aktif</p>
                <h3 class="text-3xl font-bold text-gray-900 mt-2">{{ number_format($totalActiveUsers) }}</h3>
            </div>

            <div class="w-11 h-11 rounded-xl bg-green-50 text-green-600 flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-4-4h-1M9 20H4v-2a4 4 0 014-4h1m4-5a4 4 0 110-8 4 4 0 010 8zm6 1a3 3 0 100-6" />
                </svg>
            </div>
        </div>
        <p class="text-xs text-green-600 mt-3">User dengan aktivitas</p>
    </div>
    <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm">
        <div class="flex items-start justify-between">
            <div>
                <p class="text-sm text-gray-500">Total Program Diakses</p>
                <h3 class="text-3xl font-bold text-gray-900 mt-2">{{ number_format($totalPrograms) }}</h3>
            </div>

            <div class="w-11 h-11 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4h6v6H4V4zm10 0h6v6h-6V4zM4 14h6v6H4v-6zm10 0h6v6h-6v-6z" />
                </svg>
            </div>
        </div>

        <p class="text-xs text-gray-400 mt-3">Program dengan aktivitas</p>
    </div>

    <div class="bg-white rounded-xl border border-red-200 p-5 shadow-sm">
        <div class="flex items-start justify-between">
            <div>
                <p class="text-sm text-red-500">Transaksi Hari Minggu</p>
                <h3 class="text-3xl font-bold text-red-600 mt-2">{{ number_format($sundayTransactions) }}</h3>
            </div>

            <div class="w-11 h-11 rounded-xl bg-red-50 text-red-600 flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M5.07 19h13.86c1.54 0 2.5-1.67 1.73-3L13.73 4c-.77-1.33-2.69-1.33-3.46 0L3.34 16c-.77 1.33.19 3.73 1.73 3z" />
                </svg>
            </div>
        </div>

        <p class="text-xs text-red-500 mt-3">ANOMALI</p>
    </div>
</div>

<div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
    <div class="xl:col-span-2 bg-white rounded-xl border border-gray-200 shadow-sm p-6">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h2 class="font-semibold text-gray-900">Tren Transaksi Harian</h2>
                <p class="text-xs text-gray-400 mt-1">{{ $month === 'all' && $year === 'all' ? 'Semua Periode' : ($month === 'all' ? 'Semua Bulan ' . $year : ($year === 'all' ? \Carbon\Carbon::create()->month($month)->translatedFormat('F') . ' Semua Tahun' : \Carbon\Carbon::create($year, $month)->translatedFormat('F Y'))) }}</p>
            </div>
        </div>
        <div class="h-[400px]">
            <canvas id="dailyTransactionChart"></canvas>
        </div>
    </div>
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
        <h2 class="font-semibold text-gray-900">Distribusi Trans Type</h2>
        <p class="text-xs text-gray-400 mt-1 mb-6">Berdasarkan periode terpilih</p>
        <div class="h-[400px]">
            <canvas id="transTypeChart"></canvas>
        </div>
    </div>
</div>
<div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6 mt-6">
    <div class="flex justify-between items-center mb-5">
        <div>
            <h2 class="font-semibold text-gray-900">Top 10 Program Terpopuler</h2>
            <p class="text-xs text-gray-400 mt-1">Berdasarkan jumlah transaksi</p>
        </div>

        <a href="{{ url('/program-utilization') }}" class="text-sm text-blue-600 hover:text-blue-700">Lihat semua →</a>
        </div>
    <div class="h-[350px]">
            <canvas id="topProgramChart"></canvas>
    </div>
</div>
@endsection
@push('scripts')
<script>
    window.dashboardData = {
        dailyTransactions: @json($dailyTransactions),
        topPrograms: @json($topPrograms),
        transTypes: @json($transTypes)
    };

    if (typeof window.initDashboardCharts === 'function') {
        window.initDashboardCharts();
    }
</script>
@endpush