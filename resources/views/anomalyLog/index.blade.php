@extends('layouts.app')

@section('title', 'Log Anomali')

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex items-start justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Log Anomali</h1>
            <p class="mt-1 text-sm text-gray-500">Monitoring aktivitas transaksi yang terindikasi sebagai anomali.</p>
        </div>

        <div class="flex gap-3">
            <select id="monthFilter" class="h-10 min-w-[160px] rounded-lg border border-gray-200 px-3 text-sm">
                <option value="all">Semua Bulan</option>
                @foreach(range(1, 12) as $m)
                    <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>{{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}</option>
                @endforeach
            </select>

            <select id="yearFilter" class="h-10 min-w-[140px] rounded-lg border border-gray-200 px-3 text-sm">
                <option value="all">Semua Tahun</option>
                @foreach($years as $y)
                    <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                @endforeach
            </select>
        </div>
    </div>

    {{-- KPI --}}
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-3">
        @foreach([
            ['Total Anomali', $totalAnomalies, 'fa-triangle-exclamation', 'red'],
            ['User Terlibat', $usersInvolved, 'fa-users', 'orange'],
            ['Aktivitas Minggu', $weeklyActivities, 'fa-calendar-xmark', 'amber']
        ] as [$label, $value, $icon, $color])
            <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium uppercase tracking-wide text-gray-400">{{ $label }}</p>
                        <p class="mt-2 text-2xl font-bold text-gray-900">{{ number_format($value) }}</p>
                    </div>
                    <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-{{ $color }}-50 text-{{ $color }}-500">
                        <i class="fa-solid {{ $icon }} text-lg"></i>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Chart --}}
    <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
        <h2 class="font-semibold text-gray-900">Anomali Berdasarkan User</h2>
        <p class="mt-1 text-xs text-gray-400">Top 10 user berdasarkan jumlah aktivitas Minggu.</p>
        <div class="mt-4 h-72">
            <canvas id="anomalyUserChart"></canvas>
        </div>
    </div>

    {{-- Table --}}
    <div class="rounded-xl border border-gray-200 bg-white shadow-sm">
        <div class="flex items-center justify-between border-b border-gray-100 px-6 py-5">
            <div>
                <h2 class="font-semibold text-gray-900">Daftar Log Anomali</h2>
                <p class="mt-1 text-xs text-gray-400">Seluruh aktivitas yang terjadi pada hari Minggu.</p>
            </div>

            <button class="flex h-9 items-center gap-2 rounded-lg border border-gray-200 px-3 text-sm text-gray-600 hover:bg-gray-50">
                <i class="fa-solid fa-file-excel text-green-600"></i>
                Export Excel
            </button>
        </div>

        {{-- Search --}}
        <div class="border-b border-gray-100 px-6 py-4">
            <div class="flex h-10 max-w-md items-center rounded-lg border border-gray-200 focus-within:border-blue-500">
                <i class="fa-solid fa-magnifying-glass ml-3 text-sm text-gray-400"></i>
                <input
                    id="anomalySearch"
                    value="{{ $search }}"
                    placeholder="Cari user atau program..."
                    class="h-full flex-1 border-0 px-3 text-sm outline-none focus:ring-0"
                >
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="border-b border-gray-100 bg-gray-50">
                    <tr>
                        @foreach(['No', 'Tanggal', 'Waktu', 'User', 'Program', 'Transaksi', 'Lokasi', 'Status'] as $head)
                            <th class="px-6 py-4 text-left font-semibold text-gray-500">{{ $head }}</th>
                        @endforeach
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100">
                    @forelse($anomalies as $i => $anomaly)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 text-gray-500">{{ $anomalies->firstItem() + $i }}</td>
                            <td class="px-6 py-4">{{ \Carbon\Carbon::parse($anomaly->trans_date)->format('d/m/Y') }}</td>
                            <td class="px-6 py-4">{{ $anomaly->trans_time }}</td>
                            <td class="px-6 py-4 font-medium">{{ $anomaly->user }}</td>
                            <td class="px-6 py-4 font-medium">{{ $anomaly->program }}</td>
                            <td class="px-6 py-4">{{ $anomaly->trans_type }}</td>
                            <td class="px-6 py-4">{{ $anomaly->location }}</td>
                            <td class="px-6 py-4">
                                <span class="rounded-full bg-red-50 px-2.5 py-1 text-xs font-medium text-red-700">ANOMALI</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-14 text-center">
                                <i class="fa-solid fa-shield-halved text-3xl text-red-200"></i>
                                <p class="mt-3 text-sm font-medium text-gray-500">Belum ada log anomali</p>
                                <p class="mt-1 text-xs text-gray-400">Tidak ada aktivitas hari Minggu.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($anomalies->hasPages())
            <div class="border-t border-gray-100 px-6 py-4">{{ $anomalies->links() }}</div>
        @endif
    </div>

</div>
@endsection

@push('scripts')
<script>
    window.anomalyLogData = {
        users: @json($users)
    };

    if (typeof window.initAnomalyLog === 'function') {
        window.initAnomalyLog();
    }
</script>
@endpush