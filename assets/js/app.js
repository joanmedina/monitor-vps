document.addEventListener('DOMContentLoaded', () => {
    const cpuChartCtx = document.getElementById('cpuChart').getContext('2d');
    const ramChartCtx = document.getElementById('ramChart').getContext('2d');
    const diskChartCtx = document.getElementById('diskChart').getContext('2d');
    const networkChartCtx = document.getElementById('networkChart').getContext('2d');

    // Gráfico de CPU con umbral crítico
    const cpuChart = new Chart(cpuChartCtx, {
        type: 'line',
        data: { labels: [], datasets: [{ label: 'CPU (%)', data: [], borderColor: '#007bff', backgroundColor: 'rgba(0, 123, 255, 0.1)' }] },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                annotation: {
                    annotations: {
                        threshold: {
                            type: 'line',
                            yMin: 80,
                            yMax: 80,
                            borderColor: 'red',
                            borderWidth: 2,
                            label: { content: 'Umbral Crítico (80%)', enabled: true, position: 'start' }
                        }
                    }
                }
            }
        }
    });

    const ramChart = new Chart(ramChartCtx, {
        type: 'bar',
        data: { labels: ['Usado', 'Libre'], datasets: [{ label: 'RAM (MB)', data: [], backgroundColor: ['#ffc107', '#28a745'] }] },
        options: { responsive: true, maintainAspectRatio: false }
    });

    const diskChart = new Chart(diskChartCtx, {
        type: 'doughnut',
        data: { labels: ['Usado', 'Libre'], datasets: [{ label: 'Disco (GB)', data: [], backgroundColor: ['#dc3545', '#28a745'] }] },
        options: { responsive: true, maintainAspectRatio: false }
    });

    const networkChart = new Chart(networkChartCtx, {
        type: 'bar',
        data: { labels: ['RX', 'TX'], datasets: [{ label: 'Tráfico de Red (MB)', data: [], backgroundColor: ['#007bff', '#28a745'] }] },
        options: { responsive: true, maintainAspectRatio: false }
    });

    function actualizarDatos() {
        fetch('monitor_data.php')
            .then(response => response.json())
            .then(data => {
                // Actualizar gráficos
                cpuChart.data.datasets[0].data.push(data.cpu);
                cpuChart.data.labels.push(new Date().toLocaleTimeString());
                cpuChart.update();

                ramChart.data.datasets[0].data = [data.ram.usado, data.ram.libre];
                ramChart.update();

                diskChart.data.datasets[0].data = [data.disco.usado, data.disco.libre];
                diskChart.update();

                networkChart.data.datasets[0].data = [
                    parseFloat(data.red.rx.replace(' MB', '')),
                    parseFloat(data.red.tx.replace(' MB', ''))
                ];
                networkChart.update();

                // Actualizar indicadores generales
                document.getElementById('uptime').innerText = data.uptime;
                document.getElementById('processCount').innerText = data.processes;
                document.getElementById('connectedUsers').innerText = data.users;

                // Actualizar carga del sistema
                const loadValues = data.load_average.split(',');
                const load1Min = parseFloat(loadValues[0]).toFixed(2);
                const load5Min = parseFloat(loadValues[1]).toFixed(2);
                const load15Min = parseFloat(loadValues[2]).toFixed(2);

                // Mostrar los valores en sus respectivas columnas
                document.getElementById('load1Min').innerText = load1Min;
                document.getElementById('load5Min').innerText = load5Min;
                document.getElementById('load15Min').innerText = load15Min;

                // Cambiar el color de la cabecera según el valor de 1 minuto
                const loadHeader = document.getElementById('loadHeader');
                const loadInfo = document.getElementById('loadInfo');
                console.log(loadInfo);

                if (load1Min <= 0.7) {
                    loadHeader.className = 'card-header bg-success text-white';
                    loadInfo.innerText = 'Carga baja: Rendimiento óptimo';
                } else if (load1Min > 0.7 && load1Min <= 1.5) {
                    loadHeader.className = 'card-header bg-warning text-white';
                    loadInfo.innerText = 'Carga moderada: Monitorear';
                } else {
                    loadHeader.className = 'card-header bg-danger text-white';
                    loadInfo.innerText = 'Carga alta: Posible sobrecarga';
                }

                // Actualizar datos de red
                document.getElementById('rxData').innerText = data.red.rx;
                document.getElementById('txData').innerText = data.red.tx;

                // Actualizar procesos principales
                const processTable = document.getElementById('processTable');
                processTable.innerHTML = '';
                data.procesos_principales.forEach(proceso => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${proceso.pid || 'N/A'}</td>
                        <td>${proceso.comando}</td>
                        <td>
                            ${proceso.cpu}
                            <div class="progress mt-1">
                                <div class="progress-bar bg-info" role="progressbar" style="width: ${proceso.cpu}%" aria-valuenow="${proceso.cpu}" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </td>
                        <td>
                            ${proceso.mem}
                            <div class="progress mt-1">
                                <div class="progress-bar bg-success" role="progressbar" style="width: ${proceso.mem}%" aria-valuenow="${proceso.mem}" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </td>
                    `;
                    processTable.appendChild(row);
                });

                // Actualizar particiones
                const partitionList = document.getElementById('partitionList');
                partitionList.innerHTML = '';
                data.particiones.forEach(particion => {
                    const item = document.createElement('li');
                    item.className = 'list-group-item';
                    item.innerText = `Montaje: ${particion.montaje}, Libre: ${particion.libre}, Total: ${particion.total}`;
                    partitionList.appendChild(item);
                });

                // Actualizar servicios
                const serviceList = document.getElementById('serviceList');
                serviceList.innerHTML = '';
                data.services.forEach(service => {
                    const item = document.createElement('li');
                    item.className = 'list-group-item';
                    item.innerText = `${service.name}: ${service.status}`;
                    serviceList.appendChild(item);
                });

                // Actualizar certificados SSL
                const sslList = document.getElementById('sslList');
                sslList.innerHTML = '';
                data.ssl_certificates.forEach(cert => {
                    const item = document.createElement('li');
                    item.className = 'list-group-item';
                    item.innerText = `Dominio: ${cert.dominio}, Expira: ${cert.expiracion}`;
                    sslList.appendChild(item);
                });

                // Actualizar logs
                document.getElementById('systemLogs').innerText = data.logs;
            })
            .catch(error => console.error('Error al obtener o procesar los datos:', error));
    }

    actualizarDatos();
    setInterval(actualizarDatos, 5000);
});

