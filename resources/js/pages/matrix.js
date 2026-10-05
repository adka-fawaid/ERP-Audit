import { createBarChart } from '../charts/bar';

window.initMatrixCharts = function () {
    const data = window.matrixData;

    if (!data) {
        console.warn('matrixData tidak ditemukan');
        return;
    }

    const monthFilter = document.getElementById('monthFilter');
    const yearFilter = document.getElementById('yearFilter');
    const userSearch = document.getElementById('userSearch');
    const programSearch = document.getElementById('programSearch');

    function applyFilters() {
        const params = new URLSearchParams(window.location.search);
        params.set('month', monthFilter.value);
        params.set('year', yearFilter.value);

        if (userSearch.value.trim()) params.set('user', userSearch.value.trim());
        else params.delete('user');

        if (programSearch.value.trim()) params.set('program', programSearch.value.trim());
        else params.delete('program');

        window.location.href = `${window.location.pathname}?${params.toString()}`;
    }

    monthFilter?.addEventListener('change', applyFilters);
    yearFilter?.addEventListener('change', applyFilters);

    let searchTimer;
    [userSearch, programSearch].forEach(input => input?.addEventListener('input', () => {
        clearTimeout(searchTimer);
        searchTimer = setTimeout(applyFilters, 400);
    }));

    const topUsersCanvas = document.getElementById('topUsersChart');
    
    if (topUsersCanvas && Array.isArray(data.topUsers) && data.topUsers.length) {
        createBarChart(topUsersCanvas, data.topUsers, 'user');
    }
};