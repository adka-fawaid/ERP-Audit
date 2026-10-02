<!DOCTYPE html>
<html lang="id">
<head>
    @include('layouts.head')
</head>
<body class="bg-gray-50 text-gray-800">
    @include('layouts.sidebar')
    <div id="main-wrapper" class="min-h-screen lg:ml-64">
        @include('layouts.topbar')
        <main class="p-4 sm:p-6 lg:p-8">
            @yield('content')
        </main>
        @include('layouts.footer')
    </div>
    <div id="logoutModal" class="fixed inset-0 z-[99999] hidden items-center justify-center bg-gray-900/40 p-4 backdrop-blur-sm">
        <div class="w-full max-w-sm overflow-hidden rounded-2xl bg-white shadow-2xl">
            <div class="p-6 text-center">
                <div class="mx-aut8o flex h-12 w-12 items-center justify-center rounded-full bg-red-50 text-red-500">
                    <i class="fa-solid fa-right-from-bracket text-lg"></i>
                </div>
                <h2 class="mt-4 text-lg font-bold text-gray-900">Logout?</h2>
                <p class="mt-2 text-sm leading-6 text-gray-500">
                    Apakah Anda yakin ingin keluar dari aplikasi?
                </p>
                <div class="mt-6 flex gap-3">
                    <button type="button" onclick="closeLogoutModal()" class="flex-1 rounded-lg border border-gray-200 px-4 py-2.5 text-sm font-medium text-gray-600 transition hover:bg-gray-50">Batal</button>
                    <button type="button" onclick="confirmLogout()" class="flex-1 rounded-lg bg-red-500 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-red-600">Logout</button>
                </div>
            </div>
        </div>
    </div>
    @stack('scripts')
</body>
</html>