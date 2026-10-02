import { createBarChart } from '../charts/bar';

window.initMatrixCharts = function () {
    const data = window.matrixData;

    if (!data) {
        console.warn('matrixData tidak ditemukan');
        return;
    }
    const topUsersCanvas = document.getElementById('topUsersChart');
    
    if (topUsersCanvas) {
        createBarChart(topUsersCanvas, data.topUsers, 'user');
    }
};