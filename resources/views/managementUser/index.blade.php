@extends('layouts.app')
@section('title', 'Manajemen User - QAD Audit')
@section('page-title', 'Manajemen User')
@section('content')

    @if (session('success'))
        <div id="successAlert" class="fixed top-6 right-6 z-[9999] flex w-[360px] items-center gap-3 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-green-700 shadow-lg">
            <div class="flex h-7 w-7 min-w-7 items-center justify-center rounded-full bg-green-100">
                <i class="fa-solid fa-check text-xs"></i>
            </div>

            <p class="flex-1 text-sm font-medium">{{ session('success') }}</p>

            <button type="button" onclick="closeAlert('successAlert')" class="text-xl leading-none text-green-300 hover:text-green-500">
                &times;
            </button>
        </div>
    @endif

    @if (session('error'))
        <div id="errorAlert" class="fixed top-6 right-6 z-[9999] flex w-[360px] items-center gap-3 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-red-600 shadow-lg">
            <div class="flex h-7 w-7 min-w-7 items-center justify-center rounded-full bg-red-100">
                <i class="fa-solid fa-circle-exclamation text-xs"></i>
            </div>

            <p class="flex-1 text-sm font-medium">{{ session('error') }}</p>

            <button type="button" onclick="closeAlert('errorAlert')" class="text-xl leading-none text-red-300 hover:text-red-500">
                &times;
            </button>
        </div>
    @endif

    @if ($errors->any())
        <div id="validationAlert" class="fixed top-6 right-6 z-[9999] w-[360px] rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-red-600 shadow-lg">
            <div class="flex items-start gap-3">
                <div class="flex h-7 w-7 min-w-7 items-center justify-center rounded-full bg-red-100">
                    <i class="fa-solid fa-circle-exclamation text-xs"></i>
                </div>

                <div class="flex-1">
                    @foreach ($errors->all() as $error)
                        <p class="text-sm font-medium">{{ $error }}</p>
                    @endforeach
                </div>

                <button type="button" onclick="closeAlert('validationAlert')" class="text-xl leading-none text-red-300 hover:text-red-500">
                    &times;
                </button>
            </div>
        </div>
    @endif

    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

        <div>
            <h1 class="text-2xl font-bold text-gray-900">Manajemen User</h1>
            <p class="mt-1 text-sm text-gray-500">
                Kelola akses pengguna Dashboard Audit Utilitas QAD.
            </p>
        </div>

        <button
            type="button"
            onclick="openModal()"
            class="inline-flex shrink-0 items-center justify-center gap-2 rounded-lg bg-blue-600 px-4 py-2.5 font-medium text-white transition duration-200 hover:bg-blue-700"
        >
            <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>

            <span>Tambah User</span>
        </button>

    </div>

    @include('managementUser.components.table')

    @include('managementUser.components.create-modal')

    @include('managementUser.components.view-modal')

    @include('managementUser.components.edit-modal')

    @include('managementUser.components.delete-modal')

@endsection