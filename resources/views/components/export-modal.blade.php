<div id="exportModal" class="fixed inset-0 z-[99999] hidden items-center justify-center bg-gray-900/40 p-4 backdrop-blur-sm">
    <div class="w-full max-w-lg overflow-hidden rounded-2xl bg-white shadow-2xl">
        <div class="flex items-center justify-between border-b border-gray-100 px-6 py-5">
            <div>
                <h2 class="text-lg font-semibold text-gray-900">Export Excel</h2>
                <p id="exportReportName" class="mt-1 text-sm text-gray-500">Pilih periode dan alasan export.</p>
            </div>
            <button type="button" data-export-close class="flex h-9 w-9 items-center justify-center rounded-lg text-gray-400 transition hover:bg-gray-100 hover:text-gray-700" aria-label="Tutup">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <form id="exportForm" method="POST" class="space-y-4 p-6">
            @csrf
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                    <label for="exportFromDate" class="mb-1.5 block text-sm font-medium text-gray-700">Dari tanggal</label>
                    <input id="exportFromDate" type="date" name="from_date" value="{{ now()->startOfMonth()->format('Y-m-d') }}" required class="h-10 w-full rounded-lg border border-gray-200 px-3 text-sm text-gray-700 focus:border-blue-500 focus:ring-blue-500">
                </div>
                <div>
                    <label for="exportToDate" class="mb-1.5 block text-sm font-medium text-gray-700">Sampai tanggal</label>
                    <input id="exportToDate" type="date" name="to_date" value="{{ now()->format('Y-m-d') }}" required class="h-10 w-full rounded-lg border border-gray-200 px-3 text-sm text-gray-700 focus:border-blue-500 focus:ring-blue-500">
                </div>
            </div>

            <div>
                <label for="exportReason" class="mb-1.5 block text-sm font-medium text-gray-700">Alasan</label>
                <select id="exportReason" name="reason" required class="h-10 w-full rounded-lg border border-gray-200 bg-white px-3 text-sm text-gray-700 focus:border-blue-500 focus:ring-blue-500">
                    <option value="">Pilih alasan</option>
                    <option value="audit">Audit</option>
                    <option value="department">Kebutuhan Department</option>
                    <option value="monitoring">Monitoring</option>
                    <option value="analysis">Analisis Data</option>
                    <option value="reporting">Pelaporan</option>
                    <option value="other">Lainnya</option>
                </select>
            </div>
            <div id="exportReasonOtherField" class="hidden">
                <label for="exportReasonOther" class="mb-1.5 block text-sm font-medium text-gray-700">Keterangan</label>
                <textarea id="exportReasonOther" name="reason_other" rows="3" maxlength="1000" class="w-full rounded-lg border border-gray-200 px-3 py-2 text-sm text-gray-700 focus:border-blue-500 focus:ring-blue-500"></textarea>
            </div>
            <p id="exportError" class="hidden text-sm text-red-600" role="alert"></p>
            <div class="flex justify-end gap-3 border-t border-gray-100 pt-4">
                <button type="button" data-export-close class="rounded-lg border border-gray-200 px-4 py-2.5 text-sm font-medium text-gray-600 transition hover:bg-gray-50">Batal</button>
                <button id="exportSubmit" type="submit" class="rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-blue-700">Download Excel</button>
            </div>
        </form>
    </div>
</div>