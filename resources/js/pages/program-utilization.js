document.addEventListener('DOMContentLoaded', () => {
    const monthFilter = document.getElementById('month');
    const yearFilter = document.getElementById('year');
    const programFilter = document.getElementById('programFilter');
    const transTypeFilter = document.getElementById('transTypeFilter');
    const statusFilter = document.getElementById('statusFilter');

    if (!monthFilter || !yearFilter || !programFilter || !transTypeFilter || !statusFilter) {
        return;
    }

    if (monthFilter.hasAttribute('onchange')) {
        return;
    }

    function applyFilter() {
        const params = new URLSearchParams(window.location.search);

        params.set('month', monthFilter.value);
        params.set('year', yearFilter.value);
        params.set('program', programFilter.value);
        params.set('trans_type', transTypeFilter.value);
        const status = statusFilter.value;

        params.set('status', status);

        params.delete('page');

        window.location.href = `${window.location.pathname}?${params.toString()}`;
    }

    monthFilter.addEventListener('change', applyFilter);
    yearFilter.addEventListener('change', applyFilter);
    programFilter.addEventListener('change', applyFilter);
    transTypeFilter.addEventListener('change', applyFilter);
    statusFilter.addEventListener('change', applyFilter);
});