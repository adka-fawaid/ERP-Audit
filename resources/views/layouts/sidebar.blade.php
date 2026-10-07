<aside id="sidebar" class="fixed inset-y-0 left-0 z-40 flex w-64 flex-col border-r border-gray-200 bg-white shadow-sm transition-transform duration-300">
    <div class="relative flex h-16 shrink-0 items-center justify-center border-b border-gray-200 bg-white">
        <img src="{{ asset('images/logo1.png') }}" alt="Phapros" class="h-9 w-auto object-contain">
        <button type="button" id="sidebarCollapseToggle" class="absolute flex h-8 w-8 items-center justify-center border-0 bg-transparent p-0 text-gray-600 transition hover:text-gray-900" style="right:12px; top:50%; transform:translateY(-50%);" title="Sembunyikan sidebar">
            <i class="fa-solid fa-chevron-left text-[15 px]"></i>
        </button>
    </div>
    <nav class="flex-1 overflow-y-auto px-3 py-5">
        <p class="mb-3 px-3 text-[10px] font-semibold uppercase tracking-[0.12em] text-gray-400">Monitoring</p>
        <div class="space-y-1">
            <a href="{{ route('dashboard') }}" class="group flex items-center gap-3 rounded-xl px-3 py-2.5 text-[13px] font-medium transition {{ request()->routeIs('dashboard') ? 'bg-blue-50 text-blue-700' : 'text-gray-500 hover:bg-gray-100 hover:text-gray-900' }}">
                <i class="fa-solid fa-chart-column w-5 text-center text-[17px] text-gray-500"></i>
                <span>Dashboard</span></a>
            <a href="{{ route('transaction.index') }}" class="group flex items-center gap-3 rounded-xl px-3 py-2.5 text-[13px] font-medium transition {{ request()->routeIs('transaction.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-500 hover:bg-gray-100 hover:text-gray-900' }}">
                <i class="fa-solid fa-arrow-right-arrow-left w-5 text-center text-[17px] text-gray-500"></i>
                <span>Total Transaksi</span></a>
            <a href="{{ route('qadUser.index') }}" class="group flex items-center gap-3 rounded-xl px-3 py-2.5 text-[13px] font-medium transition {{ request()->routeIs('qadUser.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-500 hover:bg-gray-100 hover:text-gray-900' }}">
                <i class="fa-solid fa-users w-5 text-center text-[17px] text-gray-500"></i>
                <span>User QAD</span></a>
            <a href="{{ route('programUtilization.index') }}" class="group flex items-center gap-3 rounded-xl px-3 py-2.5 text-[13px] font-medium transition {{ request()->routeIs('programUtilization.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-500 hover:bg-gray-100 hover:text-gray-900' }}">
                <i class="fa-solid fa-chart-line w-5 text-center text-[17px] text-gray-500"></i>
                <span>Utilisasi Program</span></a>
            <a href="{{ route('anomalyLog.index') }}" class="group flex items-center gap-3 rounded-xl px-3 py-2.5 text-[13px] font-medium transition {{ request()->routeIs('anomalyLog.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-500 hover:bg-gray-100 hover:text-gray-900' }}">
                <i class="fa-solid fa-triangle-exclamation w-5 text-center text-[17px] text-gray-500"></i>
                <span>Log Anomali</span></a>
            <a href="{{ route('accessMatrix.index') }}" class="group flex items-center gap-3 rounded-xl px-3 py-2.5 text-[13px] font-medium transition {{ request()->routeIs('accessMatrix.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-500 hover:bg-gray-100 hover:text-gray-900' }}">
                <i class="fa-solid fa-table-cells w-5 text-center text-[17px] text-gray-500"></i>
                <span>Matriks Akses</span></a>
        </div>

        @if (auth()->user()->role === 'admin')
        <div class="mt-7">
            <p class="mb-3 px-3 text-[10px] font-semibold uppercase tracking-[0.12em] text-gray-400">Administrasi</p>
            <div class="space-y-1">
                <a href="{{ route('managementUser.index') }}" class="group flex items-center gap-3 rounded-xl px-3 py-2.5 text-[13px] font-medium transition {{ request()->routeIs('managementUser.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-500 hover:bg-gray-100 hover:text-gray-900' }}">
                    <i class="fa-solid fa-users-gear w-5 text-center text-[17px] text-gray-500"></i>
                    <span>Manajemen User</span></a>
                <a href="{{ route('logActivity.index') }}" class="group flex items-center gap-3 rounded-xl px-3 py-2.5 text-[13px] font-medium transition {{ request()->routeIs('logActivity.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-500 hover:bg-gray-100 hover:text-gray-900' }}">
                    <i class="fa-solid fa-clipboard-list w-5 text-center text-[17px] text-gray-500"></i>
                    <span>Log Aktivitas</span></a>
            </div>
        </div>
    @endif
    </nav>

    <div class="shrink-0 border-t border-gray-200 bg-white p-3">
        <div class="relative overflow-hidden rounded-xl" style="background-image: linear-gradient(rgba(255,255,255,0.55), rgba(255,255,255,0.55)), url('{{ asset('images/pattern3.jpg') }}'); background-size: 100% 100%; background-position: center; background-repeat: no-repeat;">
            <div class="relative flex items-center gap-2 p-3">
                <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-blue-600 text-sm font-semibold text-white shadow-sm">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <div class="min-w-0 flex-1">
                    <p class="truncate text-[13px] font-semibold text-gray-900">{{ auth()->user()->name }}</p>
                    <p class="mt-0.5 text-[11px] text-gray-500">{{ ucfirst(auth()->user()->role) }}</p>
                </div>
                <form method="POST" action="{{ route('logout') }}" id="logoutForm">
                    @csrf
                    <button type="button" onclick="openLogoutModal()" class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl text-gray-400 transition hover:bg-red-50 hover:text-red-500" title="Logout">
                        <i class="fa-solid fa-right-from-bracket text-[16px]"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
</aside>