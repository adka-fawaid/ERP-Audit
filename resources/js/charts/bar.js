const Chart = require('chart.js/auto');

const barValueLabelPlugin = {
    id: 'barValueLabel',
    afterDatasetsDraw(chart) {
        // Plugin ini HANYA boleh berjalan
        // pada horizontal bar chart.
        if (chart.config.type !== 'bar') {
            return;
        }

        const { ctx } = chart;

        chart.data.datasets.forEach((dataset, datasetIndex) => {
            const meta =
                chart.getDatasetMeta(datasetIndex);
            meta.data.forEach((element, index) => {
                const value =
                    dataset.data[index];

                if (value === undefined || value === null) {
                    return;
                }

                ctx.save();
                const x = element.x + 8;
                const y = element.y;

                ctx.fillStyle = '#475569';
                ctx.font =
                    'bold 11px Arial';

                ctx.textAlign = 'left';
                ctx.textBaseline = 'middle';

                ctx.fillText(
                    value,
                    x,
                    y
                );

                ctx.restore();
            });
        });
    }
};

Chart.register(barValueLabelPlugin);

export function createBarChart(canvas, data, labelKey = 'program') {
    return new Chart(canvas, {
        type: 'bar',
        data: {
            labels: data.map(item => item[labelKey]),

            datasets: [{
                label: 'Jumlah Transaksi',
                data: data.map(item => Number(item.total)),
                borderWidth: 0,
                borderRadius: 4
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: false,
            layout: {
                padding: {
                    right: 45
                }
            },
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                x: {
                    beginAtZero: true,
                    grid: {
                        display: false
                    },
                    border: {
                        display: false
                    }
                },
                y: {
                    grid: {
                        display: false
                    },
                    border: {
                        display: false
                    }
                }
            }
        }
    });
}