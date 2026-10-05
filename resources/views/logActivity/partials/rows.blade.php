@forelse($logs as $log)
    <tr class="hover:bg-gray-50">
        <td class="px-5 py-3 text-sm text-gray-600">{{ $log->created_at->format('d/m/Y H:i:s') }}</td>
        <td class="px-5 py-3 text-sm font-medium text-gray-700">{{ $log->user ?? '-' }}</td>
        <td class="px-5 py-3 text-sm text-gray-600">{{ $log->user_role ?? '-' }}</td>
        <td class="px-5 py-3">
            <span class="rounded-full bg-blue-50 px-2.5 py-1 text-xs font-semibold text-blue-700">{{ $activities[$log->activity] ?? $log->activity }}</span>
        </td>
        <td class="px-5 py-3 text-sm text-gray-600">{{ $log->ip_address ?? '-' }}</td>
        <td class="px-5 py-3 text-center">
            <button type="button" onclick="showActivityDetail({{ $log->id }}, @js($log->activity), @js($log->user), @js($log->user_name), @js($log->user_role), @js($log->created_at->format('d/m/Y H:i:s')), @js($log->ip_address), @js($log->browser), @js($log->description))" class="rounded-lg px-3 py-1.5 text-xs font-medium text-blue-600 transition hover:bg-blue-50">Detail</button>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="6" class="px-5 py-16 text-center">
            <div class="flex flex-col items-center justify-center">
                <div class="flex h-12 w-12 items-center justify-center rounded-full bg-gray-100">
                    <i class="fa-solid fa-clipboard-list text-lg text-gray-400"></i>
                </div>
                <p class="mt-3 text-sm font-semibold text-gray-700">Belum ada aktivitas</p>
                <p class="mt-1 text-xs text-gray-400">Log aktivitas akan muncul di halaman ini.</p>
            </div>
        </td>
    </tr>
@endforelse
