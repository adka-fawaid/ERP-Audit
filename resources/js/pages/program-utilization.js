document.addEventListener('DOMContentLoaded', () => {
    const monthFilter = document.getElementById('month');
    const yearFilter = document.getElementById('year');
    const statusFilter = document.getElementById('statusFilter');
    const searchInput = document.getElementById('programSearch');

    if (!monthFilter || !yearFilter || !statusFilter || !searchInput) {
        return;
    }

    function applyFilter() {
        const params = new URLSearchParams(window.location.search);

        params.set('month', monthFilter.value);
        params.set('year', yearFilter.value);

        const search = searchInput.value.trim();
        const status = statusFilter.value;

        if (search) {
            params.set('search', search);
        } else {
            params.delete('search');
        }

        if (status !== 'all') {
            params.set('status', status);
        } else {
            params.delete('status');
        }

        params.delete('page');

        window.location.href = `${window.location.pathname}?${params.toString()}`;
    }

    monthFilter.addEventListener('change', applyFilter);
    yearFilter.addEventListener('change', applyFilter);
    statusFilter.addEventListener('change', applyFilter);

    let searchTimer;

    searchInput.addEventListener('input', () => {
        clearTimeout(searchTimer);
        searchTimer = setTimeout(applyFilter, 400);
    });
});