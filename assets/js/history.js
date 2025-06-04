document.addEventListener('DOMContentLoaded', () => {
    const dayCtx = document.getElementById('historyDay').getContext('2d');
    const weekCtx = document.getElementById('historyWeek').getContext('2d');
    const monthCtx = document.getElementById('historyMonth').getContext('2d');

    const createChart = ctx => new Chart(ctx, {type: 'line', data: {labels: [], datasets: [{label: 'CPU (%)', data: [], borderColor: '#007bff', backgroundColor: 'rgba(0,123,255,0.1)'}]}, options: {responsive: true, maintainAspectRatio: false}});

    const dayChart = createChart(dayCtx);
    const weekChart = createChart(weekCtx);
    const monthChart = createChart(monthCtx);

    function loadHistorical(period, chart) {
        fetch('historical_data.php?period=' + period)
            .then(r => r.json())
            .then(data => {
                chart.data.labels = data.map(d => new Date(d.timestamp * 1000).toLocaleString());
                chart.data.datasets[0].data = data.map(d => d.cpu);
                chart.update();
            });
    }

    document.getElementById('day-tab').addEventListener('shown.bs.tab', () => loadHistorical('day', dayChart));
    document.getElementById('week-tab').addEventListener('shown.bs.tab', () => loadHistorical('week', weekChart));
    document.getElementById('month-tab').addEventListener('shown.bs.tab', () => loadHistorical('month', monthChart));

    loadHistorical('day', dayChart);
});
