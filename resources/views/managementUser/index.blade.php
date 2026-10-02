@extends('layouts.app')
@section('title', 'Manajemen User - QAD Audit')
@section('page-title', 'Manajemen User')
@section('content')

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Manajemen User</h1>
            <p class="text-sm text-gray-500 mt-1">Kelola akses pengguna Dashboard Audit Utilitas QAD.</p>
        </div>

        <button type="button" onclick="openModal()" class="inline-flex shrink-0 items-center justify-center gap-2 rounded-lg bg-blue-600 px-4 py-2.5 font-medium text-white transition duration-200 hover:bg-blue-700">
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

@if (session('success'))
    <div id="successAlert" style="position: fixed; top: 24px; right: 24px; z-index: 9999; width: 360px; display: flex; align-items: center; gap: 12px; padding: 14px 16px; background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 10px; color: #15803d; box-shadow: 0 10px 25px rgba(0,0,0,0.10);">
        <div style="width: 28px; height: 28px; min-width: 28px; display: flex; align-items: center; justify-content: center; background: #dcfce7; border-radius: 50%;">
            <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
        </div>
        <p style="flex: 1; margin: 0; font-size: 14px; font-weight: 500;">{{ session('success') }}</p>
        <button type="button" onclick="closeAlert('successAlert')" style="border: none; background: transparent; color: #86efac; font-size: 20px; cursor: pointer; line-height: 1;">&times;</button>
    </div>
@endif

@if ($errors->any())
    <div id="errorAlert" style="position: fixed; top: 24px; right: 24px; z-index: 9999; width: 360px; display: flex; align-items: center; gap: 12px; padding: 14px 16px; background: #fef2f2; border: 1px solid #fecaca; border-radius: 10px; color: #dc2626; box-shadow: 0 10px 25px rgba(0,0,0,0.10);">
        <div style="width: 28px; height: 28px; min-width: 28px; display: flex; align-items: center; justify-content: center; background: #fee2e2; border-radius: 50%;">
            <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01" />
            </svg>
        </div>
        <div style="flex: 1;">
            @foreach ($errors->all() as $error)
                <p style="margin: 0; font-size: 14px; font-weight: 500;">{{ $error }}</p>
            @endforeach
        </div>
        <button type="button" onclick="closeAlert('errorAlert')" style="border: none; background: transparent; color: #fca5a5; font-size: 20px; cursor: pointer; line-height: 1;">&times;</button>
    </div>
@endif

@endsection