@extends('layouts.app')

@section('title', 'User QAD - QAD Audit')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">User QAD</h1>
            <p class="mt-1 text-sm text-gray-500">User yang benar-benar melakukan transaksi QAD pada periode terpilih.</p>
        </div>
        <div class="flex gap-3">
        <form method="GET" action="{{ route('qadUser.index') }}" class="flex gap-3">
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
        <button type="button" data-export-url="{{ route('qadUser.export') }}" data-export-report="User QAD" class="flex h-10 items-center gap-2 rounded-lg border border-gray-200 bg-white px-3 text-sm font-medium text-gray-600 transition hover:bg-gray-50">
            <i class="fa-solid fa-file-excel text-green-600"></i>
            Export Excel
        </button>
        </div>
    </div>

    <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
        <div class="border-b border-gray-100 px-6 py-5">
            <form method="GET" action="{{ route('qadUser.index') }}" class="flex flex-col gap-3 sm:flex-row sm:items-center">
                <select name="user" onchange="this.form.submit()" class="h-10 w-full rounded-lg border border-gray-200 bg-white px-3 text-sm text-gray-700 focus:border-blue-500 focus:ring-blue-500 sm:max-w-md">
                    <option value="">Semua User</option>
                    @foreach($availableUsers as $availableUser)
                        <option value="{{ $availableUser->tr_user }}" {{ $userFilter === $availableUser->tr_user ? 'selected' : '' }}>
                            {{ $availableUser->tr_user }}{{ $availableUser->nama ? ' - ' . $availableUser->nama : '' }}
                        </option>
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
                        <th class="px-6 py-4 text-left font-semibold text-gray-500">User</th>
                        <th class="px-6 py-4 text-left font-semibold text-gray-500">Total Transaksi</th>
                        <th class="px-6 py-4 text-left font-semibold text-gray-500">Aktivitas Terakhir</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($users as $index => $user)
                        <tr class="transition hover:bg-gray-50">
                            <td class="px-6 py-4 text-gray-500">{{ $users->firstItem() + $index }}</td>
                            <td class="px-6 py-4 font-medium text-gray-900">{{ $user->user }}</td>
                            <td class="px-6 py-4 font-semibold text-gray-700">{{ number_format($user->total) }}</td>
                            <td class="px-6 py-4 text-gray-600">
                                <div>{{ $user->last_trans_type }} &bull; {{ $user->last_program }}</div>
                                @if($user->last_trans_date)
                                    <div class="mt-1 text-xs text-gray-400">{{ \Carbon\Carbon::parse($user->last_trans_date)->format('d-m-Y') }} {{ $user->last_trans_time }}</div>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="px-6 py-14 text-center text-sm text-gray-500">Tidak ada user QAD pada periode yang dipilih.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($users->hasPages())
            <div class="border-t border-gray-100 px-6 py-4">{{ $users->links() }}</div>
        @endif
    </div>
</div>
@endsection
