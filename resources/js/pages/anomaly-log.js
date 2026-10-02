import { createBarChart } from '../charts/bar';

window.initAnomalyLog = function () {
    const data = window.anomalyLogData;

    if (!data) {
        return;
    }

    const month = document.getElementById('monthFilter');
    const year = document.getElementById('yearFilter');
    const search = document.getElementById('anomalySearch');

    function applyFilter() {
        const params = new URLSearchParams();

        params.set('month', month.value);
        params.set('year', year.value);

        if (search.value.trim()) {
            params.set('search', search.value.trim());
        }

        window.location.href = `${window.location.pathname}?${params}`;
    }

    month.addEventListener('change', applyFilter);
    year.addEventListener('change', applyFilter);

    let timer;

    search.addEventListener('input', () => {
        clearTimeout(timer);
        timer = setTimeout(applyFilter, 400);
    });

    const canvas = document.getElementById('anomalyUserChart');

    if (canvas && data.users.length) {
        createBarChart(canvas, data.users, 'user');
    }
};