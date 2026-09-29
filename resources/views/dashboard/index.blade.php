@extends('layouts.app')
@section('title', 'Dashboard Utama - QAD Audit')
@section('page-title', 'Dashboard Utama')
@section('content')
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900"> Dashboard Utama </h1>
            <p class="text-sm text-gray-500 mt-1"> Monitoring dan audit utilisasi ERP QAD </p>

        </div>
        <div class="flex gap-3">
            <select class="bg-white border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500">
                <option>September</option>
                <option>Agustus</option>
                <option>Juli</option>
            </select>
            <select
                class="bg-white border border-gray-200
                       rounded-lg px-4 py-2.5 text-sm
                       focus:ring-2 focus:ring-blue-500"
            >
                <option>2026</option>
                <option>2025</option>
            </select>

        </div>

    </div>


    {{-- ===================================================== --}}
    {{-- KPI --}}
    {{-- ===================================================== --}}

    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5 mb-6">


        {{-- TOTAL TRANSAKSI --}}
        <div class="bg-white rounded-xl border border-gray-200
                    p-5 shadow-sm">

            <div class="flex items-start justify-between">

                <div>

                    <p class="text-sm text-gray-500">
                        Total Transaksi
                    </p>

                    <h3 class="text-3xl font-bold text-gray-900 mt-2">
                        12,847
                    </h3>

                </div>

                <div
                    class="w-11 h-11 rounded-xl
                           bg-blue-50 text-blue-600
                           flex items-center justify-center"
                >
                    <svg
                        class="w-6 h-6"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M3 3v18h18M7 16v-5m5 5V7m5 9v-8"
                        />
                    </svg>
                </div>

            </div>

            <p class="text-xs text-green-600 mt-3">
                Periode September 2026
            </p>

        </div>


        {{-- TOTAL USER --}}
        <div class="bg-white rounded-xl border border-gray-200
                    p-5 shadow-sm">

            <div class="flex items-start justify-between">

                <div>

                    <p class="text-sm text-gray-500">
                        Total User Aktif
                    </p>

                    <h3 class="text-3xl font-bold text-gray-900 mt-2">
                        23
                    </h3>

                </div>

                <div
                    class="w-11 h-11 rounded-xl
                           bg-green-50 text-green-600
                           flex items-center justify-center"
                >
                    <svg
                        class="w-6 h-6"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M17 20h5v-2a4 4 0 00-4-4h-1M9 20H4v-2a4 4 0 014-4h1m4-5a4 4 0 110-8 4 4 0 010 8zm6 1a3 3 0 100-6"
                        />
                    </svg>
                </div>

            </div>

            <p class="text-xs text-green-600 mt-3">
                User dengan aktivitas
            </p>

        </div>


        {{-- TOTAL PROGRAM --}}
        <div class="bg-white rounded-xl border border-gray-200
                    p-5 shadow-sm">

            <div class="flex items-start justify-between">

                <div>

                    <p class="text-sm text-gray-500">
                        Total Program Diakses
                    </p>

                    <h3 class="text-3xl font-bold text-gray-900 mt-2">
                        18
                    </h3>

                </div>

                <div
                    class="w-11 h-11 rounded-xl
                           bg-purple-50 text-purple-600
                           flex items-center justify-center"
                >
                    <svg
                        class="w-6 h-6"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M4 4h6v6H4V4zm10 0h6v6h-6V4zM4 14h6v6H4v-6zm10 0h6v6h-6v-6z"
                        />
                    </svg>
                </div>

            </div>

            <p class="text-xs text-gray-400 mt-3">
                Program dengan aktivitas
            </p>

        </div>


        {{-- ANOMALI --}}
        <div
            class="bg-white rounded-xl border border-red-200
                   p-5 shadow-sm"
        >

            <div class="flex items-start justify-between">

                <div>

                    <p class="text-sm text-red-500">
                        Transaksi Hari Minggu
                    </p>

                    <h3 class="text-3xl font-bold text-red-600 mt-2">
                        342
                    </h3>

                </div>

                <div
                    class="w-11 h-11 rounded-xl
                           bg-red-50 text-red-600
                           flex items-center justify-center"
                >

                    <svg
                        class="w-6 h-6"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M12 9v2m0 4h.01M5.07 19h13.86c1.54 0 2.5-1.67 1.73-3L13.73 4c-.77-1.33-2.69-1.33-3.46 0L3.34 16c-.77 1.33.19 3.73 1.73 3z"
                        />
                    </svg>

                </div>

            </div>

            <p class="text-xs text-red-500 mt-3">
                ANOMALI
            </p>

        </div>

    </div>


    {{-- ===================================================== --}}
    {{-- CHART AREA PLACEHOLDER --}}
    {{-- ===================================================== --}}

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">


        {{-- TREND --}}
        <div
            class="xl:col-span-2 bg-white rounded-xl
                   border border-gray-200 shadow-sm p-6"
        >

            <div class="flex justify-between items-center mb-6">

                <div>

                    <h2 class="font-semibold text-gray-900">
                        Tren Transaksi Harian
                    </h2>

                    <p class="text-xs text-gray-400 mt-1">
                        September 2026
                    </p>

                </div>

            </div>


            {{-- TEMPORARY CHART --}}
            <div
                class="h-72 bg-gray-50 rounded-lg
                       flex items-center justify-center"
            >

                <p class="text-gray-400">
                    Chart transaksi akan ditampilkan di sini
                </p>

            </div>

        </div>


        {{-- TRANS TYPE --}}
        <div
            class="bg-white rounded-xl
                   border border-gray-200 shadow-sm p-6"
        >

            <h2 class="font-semibold text-gray-900">
                Distribusi Trans Type
            </h2>

            <p class="text-xs text-gray-400 mt-1 mb-6">
                Berdasarkan periode terpilih
            </p>


            <div
                class="h-72 bg-gray-50 rounded-lg
                       flex items-center justify-center"
            >

                <p class="text-gray-400">
                    Donut chart
                </p>

            </div>

        </div>

    </div>


    {{-- ===================================================== --}}
    {{-- TOP PROGRAM --}}
    {{-- ===================================================== --}}

    <div
        class="bg-white rounded-xl border border-gray-200
               shadow-sm p-6 mt-6"
    >

        <div class="flex justify-between items-center mb-5">

            <div>

                <h2 class="font-semibold text-gray-900">
                    Top 10 Program Terpopuler
                </h2>

                <p class="text-xs text-gray-400 mt-1">
                    Berdasarkan jumlah transaksi
                </p>

            </div>

            <a
                href="#"
                class="text-sm text-blue-600 hover:text-blue-700"
            >
                Lihat semua →
            </a>

        </div>


        <div class="space-y-4">

            @php
                $programs = [
                    ['name' => 'poporc.p', 'value' => 95],
                    ['name' => 'sosomt.p', 'value' => 84],
                    ['name' => 'inmmt.p', 'value' => 72],
                    ['name' => 'glglmt.p', 'value' => 64],
                    ['name' => 'sosoet.p', 'value' => 55],
                    ['name' => 'unhmt.p', 'value' => 49],
                    ['name' => 'inmmt.p', 'value' => 43],
                    ['name' => 'glnnmt.p', 'value' => 37],
                    ['name' => 'inmtrmt.p', 'value' => 32],
                    ['name' => 'sosoet.p', 'value' => 25],
                ];
            @endphp


            @foreach ($programs as $program)

                <div class="flex items-center gap-4">

                    <div class="w-24 text-sm text-gray-600 truncate">
                        {{ $program['name'] }}
                    </div>

                    <div
                        class="flex-1 h-2 bg-gray-100
                               rounded-full overflow-hidden"
                    >

                        <div
                            class="h-full bg-blue-500 rounded-full"
                            style="width: {{ $program['value'] }}%;"
                        ></div>

                    </div>

                    <div class="w-10 text-right text-sm font-medium">
                        {{ $program['value'] }}
                    </div>

                </div>

            @endforeach

        </div>

    </div>

@endsection