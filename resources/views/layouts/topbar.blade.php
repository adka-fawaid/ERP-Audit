<header class="relative sticky top-0 z-40 flex h-16 items-center justify-between border-b border-gray-200 bg-white px-4 sm:px-6 lg:px-8">
    <div class="flex items-center gap-4">
        <button type="button" id="sidebarShowToggle" class="hidden h-9 w-9 shrink-0 items-center justify-center border-0 bg-transparent p-0 text-gray-600 transition hover:text-gray-900" title="Tampilkan sidebar">
            <i class="fa-solid fa-angle-right text-[20px]"></i>
        </button>
        <div id="headerTitle">
            <h2 class="text-lg font-bold text-gray-800">QAD Audit</h2>
        </div>
    </div>
    <div class="flex flex-col items-end text-right">
        <p id="serverDateTime" data-server-time="{{ now()->timezone('Asia/Jakarta')->timestamp }}" class="mt-0.5 text-[10px] font-semibold text-gray-900">{{ now()->timezone('Asia/Jakarta')->format('d F Y, H:i:s') }} WIB</p>
    </div>
</header>