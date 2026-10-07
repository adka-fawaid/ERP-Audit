@extends('layouts.app')

@section('title', 'Total Transaksi - QAD Audit')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Total Transaksi</h1>
            <p class="mt-1 text-sm text-gray-500">Daftar transaksi QAD pada periode yang dipilih.</p>
        </div>

        <div class="flex gap-3">
            <form method="GET" action="{{ route('transaction.index') }}" class="flex gap-3">
                <select name="month" onchange="this.form.submit()" class="h-10 min-w-[160px] rounded-lg border border-gray-200 bg-gray-100 px-3 text-sm text-gray-700 focus:border-blue-500 focus:ring-blue-500">
                    <option value="all" {{ $month === 'all' ? 'selected' : '' }}>Semua Bulan</option>
                    @foreach(range(1, 12) as $monthNumber)
                        <option value="{{ $monthNumber }}" {{ (string) $month === (string) $monthNumber ? 'selected' : '' }}>{{ \Carbon\Carbon::create()->month($monthNumber)->translatedFormat('F') }}</option>
                    @endforeach
                </select>
                <select name="year" onchange="this.form.submit()" class="h-10 min-w-[140px] rounded-lg border border-gray-200 bg-gray-100 px-3 text-sm text-gray-700 focus:border-blue-500 focus:ring-blue-500">
                    <option value="all" {{ $year === 'all' ? 'selected' : '' }}>Semua Tahun</option>
                    @foreach($years as $yearOption)
                        <option value="{{ $yearOption }}" {{ (string) $year === (string) $yearOption ? 'selected' : '' }}>{{ $yearOption }}</option>
                    @endforeach
                </select>
            </form>
            <button type="button" data-export-url="{{ route('transaction.export') }}" data-export-report="Total Transaksi" class="flex h-10 items-center gap-2 rounded-lg border border-gray-200 bg-white px-3 text-sm font-medium text-gray-600 transition hover:bg-gray-50">
                <i class="fa-solid fa-file-excel text-green-600"></i>
                Export Excel
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-6 xl:grid-cols-2">
        <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
            <h2 class="font-semibold text-gray-900">Distribusi Transaksi Berdasarkan Tanggal</h2>
            <div class="mt-5 h-[320px]"><canvas id="transactionDailyChart"></canvas></div>
        </div>
        <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
            <h2 class="font-semibold text-gray-900">Distribusi Berdasarkan Trans Type</h2>
            <div class="mt-5 h-[320px]"><canvas id="transactionTypeChart"></canvas></div>
        </div>
    </div>

    <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
        <div class="flex flex-col gap-4 border-b border-gray-100 px-6 py-5 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <h2 class="font-semibold text-gray-900">Daftar Transaksi</h2>
                <p class="mt-1 text-xs text-gray-400">{{ number_format($transactions->total()) }} transaksi pada periode terpilih.</p>
            </div>
            <form method="GET" action="{{ route('transaction.index') }}" class="flex flex-col gap-2 sm:flex-row sm:items-center">
                <select name="trans_type" onchange="this.form.submit()" class="h-10 rounded-lg border border-gray-200 bg-white px-3 text-sm text-gray-700 focus:border-blue-500 focus:ring-blue-500">
                    <option value="">Semua Trans Type</option>
                    @foreach($transactionTypes as $option)
                        <option value="{{ $option }}" {{ $transType === $option ? 'selected' : '' }}>{{ $option }}</option>
                    @endforeach
                </select>
                <select name="program" onchange="this.form.submit()" class="h-10 rounded-lg border border-gray-200 bg-white px-3 text-sm text-gray-700 focus:border-blue-500 focus:ring-blue-500">
                    <option value="">Semua Program</option>
                    @foreach($programs as $option)
                        <option value="{{ $option }}" {{ $program === $option ? 'selected' : '' }}>{{ $option }}</option>
                    @endforeach
                </select>
                <select name="user" onchange="this.form.submit()" class="h-10 rounded-lg border border-gray-200 bg-white px-3 text-sm text-gray-700 focus:border-blue-500 focus:ring-blue-500">
                    <option value="">Semua User</option>
                    @foreach($users as $option)
                        <option value="{{ $option }}" {{ $user === $option ? 'selected' : '' }}>{{ $option }}</option>
                    @endforeach
                </select>
                <input type="hidden" name="month" value="{{ $month }}">
                <input type="hidden" name="year" value="{{ $year }}">
            </form>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="border-b border-gray-100 bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-left font-semibold text-gray-500">No</th>
                        <th class="px-6 py-4 text-left font-semibold text-gray-500">Trans Number</th>
                        <th class="px-6 py-4 text-left font-semibold text-gray-500">User</th>
                        <th class="px-6 py-4 text-left font-semibold text-gray-500">Program</th>
                        <th class="px-6 py-4 text-left font-semibold text-gray-500">Trans Type</th>
                        <th class="px-6 py-4 text-left font-semibold text-gray-500">Tanggal</th>
                        <th class="px-6 py-4 text-left font-semibold text-gray-500">Waktu</th>
                        <th class="px-6 py-4 text-left font-semibold text-gray-500">Location</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($transactions as $index => $transaction)
                        <tr class="transition hover:bg-gray-50">
                            <td class="whitespace-nowrap px-6 py-4 text-gray-500">{{ $transactions->firstItem() + $index }}</td>
                            <td class="whitespace-nowrap px-6 py-4 font-medium text-gray-900">{{ $transaction->trans_number }}</td>
                            <td class="whitespace-nowrap px-6 py-4 text-gray-600">{{ $transaction->tr_user }}</td>
                            <td class="whitespace-nowrap px-6 py-4 text-gray-600">{{ $transaction->program }}</td>
                            <td class="whitespace-nowrap px-6 py-4 text-gray-600">{{ $transaction->trans_type }}</td>
                            <td class="whitespace-nowrap px-6 py-4 text-gray-600">{{ optional($transaction->trans_date)->format('d-m-Y') }}</td>
                            <td class="whitespace-nowrap px-6 py-4 text-gray-600">{{ $transaction->trans_time }}</td>
                            <td class="whitespace-nowrap px-6 py-4 text-gray-600">{{ $transaction->location }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="px-6 py-14 text-center text-sm text-gray-500">Tidak ada transaksi pada periode yang dipilih.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($transactions->hasPages())
            <div class="border-t border-gray-100 px-6 py-4">{{ $transactions->links() }}</div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
    window.transactionData = {
        dailyTransactions: @json($dailyTransactions),
        transTypes: @json($transTypes)
    };

    if (typeof window.initTransactionCharts === 'function') {
        window.initTransactionCharts();
    }
</script>
@endpush
