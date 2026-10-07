import { createLineChart } from '../charts/line';
import { createDonutChart } from '../charts/donut';

document.addEventListener('DOMContentLoaded', () => {
    window.initTransactionCharts = function () {
        const data = window.transactionData;

        if (!data) return;

        const dailyCanvas = document.getElementById('transactionDailyChart');
        if (dailyCanvas) {
            createLineChart(dailyCanvas, data.dailyTransactions);
        }

        const typeCanvas = document.getElementById('transactionTypeChart');
        if (typeCanvas) {
            createDonutChart(typeCanvas, data.transTypes);
        }
    };

    if (window.transactionData) {
        window.initTransactionCharts();
    }
});
