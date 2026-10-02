import { createLineChart } from '../charts/line';
import { createDonutChart } from '../charts/donut';
import { createBarChart } from '../charts/bar';


window.initDashboardCharts = function () {

    const data = window.dashboardData;

    if (!data) {
        console.warn('dashboardData tidak ditemukan');
        return;
    }


    // =========================
    // TREND TRANSAKSI
    // =========================

    const dailyCanvas =
        document.getElementById('dailyTransactionChart');

    if (dailyCanvas) {

        createLineChart(
            dailyCanvas,
            data.dailyTransactions
        );
    }


    // =========================
    // DISTRIBUSI TRANS TYPE
    // =========================

    const typeCanvas =
        document.getElementById('transTypeChart');

    if (typeCanvas) {

        createDonutChart(
            typeCanvas,
            data.transTypes
        );
    }


    // =========================
    // TOP 10 PROGRAM
    // =========================

    const programCanvas =
        document.getElementById('topProgramChart');

    if (programCanvas) {

        createBarChart(
            programCanvas,
            data.topPrograms
        );
    }
};