const Chart = require('chart.js/auto');

const monthNames = [
    'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun',
    'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'
];

const formatDate = (date) => {
    const value = String(date).substring(0, 10);
    const day = value.substring(8, 10);
    const month = Number(value.substring(5, 7));
    return `${day} ${monthNames[month - 1]}`;
};

export function createLineChart(canvas, transactions) {
    return new Chart(canvas, {
        type: 'line',
        data: {
            labels: transactions.map(item =>
                formatDate(item.trans_date)
            ),
            datasets: [{
                label: 'Transaksi',
                data: transactions.map(item =>
                    Number(item.total)
                ),
                borderWidth: 2.5,
                tension: 0.35,
                fill: true,
                backgroundColor:
                    'rgba(37, 99, 235, 0.08)',
                pointRadius: 3,
                pointHoverRadius: 6,
                pointBackgroundColor:
                    transactions.map(item => {
                        const date =
                            String(item.trans_date)
                                .substring(0, 10);
                        const day = new Date(
                            `${date}T00:00:00`
                        ).getDay();
                        return day === 0
                            ? '#ef4444'
                            : '#2563eb';
                    }),
                segment: {
                    borderColor: ctx => {
                        const index =
                            ctx.p1DataIndex;
                        const item =
                            transactions[index];
                        if (!item) {
                            return '#2563eb';
                        }
                        const date =
                            String(item.trans_date)
                                .substring(0, 10);
                        const day = new Date(
                            `${date}T00:00:00`
                        ).getDay();
                        return day === 0
                            ? '#ef4444'
                            : '#2563eb';
                    }
                }
            }]
        },

        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    enabled: true,
                    callbacks: {
                        label: context =>
                            ` ${context.parsed.y} transaksi`
                    }
                }
            },
            scales: {
                x: {
                    grid: {
                        display: false
                    },
                    border: {
                        display: true
                    },

                    ticks: {
                        autoSkip: true,
                        maxTicksLimit: 15
                    }
                },
                y: {
                    beginAtZero: true,
                    grid: {
                        display: true,
                        color: 'rgba(148, 163, 184, 0.12)'
                    },
                    border: {
                        display: false
                    }
                }
            }
        }
    });
}