const Chart = require('chart.js/auto');

const donutValueLabelPlugin = {
    id: 'donutValueLabel',
    afterDatasetsDraw(chart) {
        // Plugin INI HANYA untuk donut.
        // Jangan sampai ikut jalan di line/bar.
        if (chart.config.type !== 'doughnut') {
            return;
        }

        const { ctx } = chart;
        chart.data.datasets.forEach((dataset, datasetIndex) => {
            const meta =
                chart.getDatasetMeta(datasetIndex);
            const total = dataset.data.reduce(
                (sum, item) => sum + Number(item),
                0
            );
            meta.data.forEach((element, index) => {
                const value =
                    Number(dataset.data[index]);

                if (!value) {
                    return;
                }
                const percentage =
                    ((value / total) * 100).toFixed(1);
                const angle =
                    element.startAngle +
                    (element.endAngle - element.startAngle) / 2;
                const radius =
                    (element.outerRadius +
                        element.innerRadius) / 2;
                const x =
                    element.x +
                    Math.cos(angle) * radius;
                const y =
                    element.y +
                    Math.sin(angle) * radius;
                ctx.save();
                ctx.fillStyle = '#ffffff';
                ctx.font =
                    'bold 12px Arial';
                ctx.textAlign = 'center';
                ctx.textBaseline = 'middle';
                ctx.fillText(
                    `${value}`,
                    x,
                    y - 8
                );
                ctx.fillText(
                    `${percentage}%`,
                    x,
                    y + 8
                );
                ctx.restore();
            });
        });
    }
};

Chart.register(donutValueLabelPlugin);

export function createDonutChart(canvas, transTypes) {
    return new Chart(canvas, {
        type: 'doughnut',
        data: {
            labels: transTypes.map(item =>
                item.trans_type
            ),
            datasets: [{
                data: transTypes.map(item =>
                    Number(item.total)
                ),
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            radius: '92%',
            cutout: '48%',
            layout: {
                padding: {
                    top: 0,
                    bottom: 0,
                    left: 0,
                    right: 0
                }
            },
            plugins: {
                legend: {
                    display: true,
                    position: 'bottom',
                    align: 'center',
                    labels: {
                        boxWidth: 20,
                        boxHeight: 12,
                        padding: 12,
                        font: {
                            size: 12
                        }
                    }
                },
                tooltip: {
                    enabled: true
                }
            }
        }
    });
}