window.showActivityDetail = function (id, activity, user, userName, role, time, ip, browser, description) {
    document.getElementById('detailActivity').textContent = activity || '-';
    document.getElementById('detailUser').textContent = userName || user || '-';
    document.getElementById('detailRole').textContent = role || '-';
    document.getElementById('detailTime').textContent = time || '-';
    document.getElementById('detailIp').textContent = ip || '-';
    document.getElementById('detailBrowser').textContent = browser || '-';
    document.getElementById('detailDescription').textContent = description || '-';

    const modal = document.getElementById('activityDetailModal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    document.body.classList.add('overflow-hidden');
};

window.closeActivityDetail = function () {
    const modal = document.getElementById('activityDetailModal');

    if (!modal) return;

    modal.classList.remove('flex');
    modal.classList.add('hidden');
    document.body.classList.remove('overflow-hidden');
};

document.addEventListener('DOMContentLoaded', () => {
    const modal = document.getElementById('activityDetailModal');
    const form = document.getElementById('activityLogFilters');
    const rows = document.getElementById('activityLogRows');
    const pagination = document.getElementById('activityLogPagination');
    const reset = document.getElementById('activityLogReset');
    let requestController;
    let searchTimer;
    let currentParams = new URLSearchParams(window.location.search);

    if (!form || !rows || !pagination) return;

    async function refreshLogs(params = currentParams, updateUrl = false) {
        requestController?.abort();
        requestController = new AbortController();

        const url = new URL(form.action, window.location.href);
        url.search = params.toString();

        try {
            const response = await fetch(url, {
                headers: {
                    Accept: 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                signal: requestController.signal
            });

            if (!response.ok) throw new Error(`HTTP ${response.status}`);

            const data = await response.json();
            rows.innerHTML = data.rows;
            pagination.innerHTML = data.pagination;
            document.getElementById('activityLogTotal').textContent = `${new Intl.NumberFormat().format(data.total)} Aktivitas`;
            document.getElementById('activityLogRange').textContent = `Menampilkan ${data.firstItem ?? 0}-${data.lastItem ?? 0} dari ${data.total} aktivitas`;

            currentParams = new URLSearchParams(params);
            const hasFilters = ['search', 'activity', 'user', 'start_date', 'end_date']
                .some(name => currentParams.get(name));
            reset.classList.toggle('hidden', !hasFilters);

            if (updateUrl) {
                window.history.replaceState({}, '', `${url.pathname}${url.search}`);
            }
        } catch (error) {
            if (error.name !== 'AbortError') console.error('Gagal memperbarui log aktivitas:', error);
        }
    }

    function applyFilters() {
        const params = new URLSearchParams(new FormData(form));
        params.delete('page');
        refreshLogs(params, true);
    }

    form?.addEventListener('submit', event => {
        event.preventDefault();
        applyFilters();
    });

    form?.querySelectorAll('select, input[type="date"]').forEach(input => {
        input.addEventListener('change', applyFilters);
    });

    form?.querySelector('input[name="search"]')?.addEventListener('input', () => {
        clearTimeout(searchTimer);
        searchTimer = setTimeout(applyFilters, 300);
    });

    reset?.addEventListener('click', event => {
        event.preventDefault();
        event.stopPropagation();
        form.reset();
        applyFilters();
    });


    pagination?.addEventListener('click', event => {
        const link = event.target.closest('a');

        if (!link) return;

        event.preventDefault();
        refreshLogs(new URL(link.href).searchParams, true);
    });

    window.setInterval(() => {
        if (!document.hidden) refreshLogs();
    }, 15000);

    modal?.addEventListener('click', event => {
        if (event.target === modal) {
            window.closeActivityDetail();
        }
    });

    document.addEventListener('keydown', event => {
        if (event.key === 'Escape') {
            window.closeActivityDetail();
        }
    });
});