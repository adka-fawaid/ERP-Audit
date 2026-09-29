<header class="sticky top-0 z-40 flex h-16 items-center justify-between border-b border-gray-200 bg-white px-4 sm:px-6 lg:px-8">
    <div class="flex items-center gap-4">
        <button type="button" id="sidebarShowToggle" class="h-9 w-9 items-center justify-center rounded-lg text-gray-500 transition hover:bg-gray-100 hover:text-gray-800" title="Tampilkan sidebar">
            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor">
                <circle cx="5" cy="12" r="1.5" />
                <circle cx="12" cy="12" r="1.5" />
                <circle cx="19" cy="12" r="1.5" />
            </svg>
        </button>
        <div>
            <h2 class="text-lg font-bold text-gray-800">QAD Audit</h2>
        </div>
    </div>
    <div class="flex flex-col items-end text-right">
        <p id="serverDateTime" data-server-time="{{ now()->timezone('Asia/Jakarta')->timestamp }}" class="mt-0.5 text-[10px] font-semibold text-gray-900">{{ now()->timezone('Asia/Jakarta')->format('d F Y, H:i:s') }} WIB</p>
    </div>
</header>