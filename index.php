<?php
require_once 'auth.php';
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monitorización del VPS</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/styles.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-annotation"></script>
    <script src="assets/js/app.js" defer></script>
    <script src="assets/js/history.js" defer></script>
</head>
<body class="bg-light">

    <div class="container py-5">
        <h1 class="text-center mb-5">Monitorización del Servidor VPS</h1>

        <ul class="nav nav-tabs" id="mainTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="live-tab" data-bs-toggle="tab" data-bs-target="#live" type="button" role="tab">En Vivo</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="history-tab" data-bs-toggle="tab" data-bs-target="#history" type="button" role="tab">Histórico</button>
            </li>
        </ul>
        <div class="tab-content pt-4" id="mainTabContent">
            <div class="tab-pane fade show active" id="live" role="tabpanel">

        <!-- Indicadores Generales -->
        <div class="row row-cols-1 row-cols-md-3 g-3 mb-4">
            <div class="col">
                <div class="card text-center shadow-sm border-0 bg-warning text-white">
                    <div class="card-body">
                        <h5 class="card-title">Uptime</h5>
                        <h3 id="uptime" class="fw-bold">Cargando...</h3>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card text-center shadow-sm border-0 bg-info text-white">
                    <div class="card-body">
                        <h5 class="card-title">Procesos Activos</h5>
                        <h3 id="processCount" class="fw-bold">Cargando...</h3>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card text-center shadow-sm border-0 bg-danger text-white">
                    <div class="card-body">
                        <h5 class="card-title">Usuarios Conectados</h5>
                        <h3 id="connectedUsers" class="fw-bold">Cargando...</h3>
                    </div>
                </div>
            </div>
        </div>
        
<!-- Carga del Sistema -->
<div class="row g-4 mb-4">
    <div class="col-md-12">
        <div class="card shadow-sm">
            <div class="card-header bg-secondary text-white" id="loadHeader">Carga del Sistema</div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-4">
                        <h6>1 Min</h6>
                        <h4 id="load1Min" class="fw-bold">-</h4>
                    </div>
                    <div class="col-4">
                        <h6>5 Min</h6>
                        <h4 id="load5Min" class="fw-bold">-</h4>
                    </div>
                    <div class="col-4">
                        <h6>15 Min</h6>
                        <h4 id="load15Min" class="fw-bold">-</h4>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col text-center">
                        <p id="loadInfo" class="text-muted small">Estado: Cargando...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



        <!-- Gráficos -->
        <div class="row g-4">
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">CPU</div>
                    <div class="card-body">
                        <canvas id="cpuChart"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">RAM</div>
                    <div class="card-body">
                        <canvas id="ramChart"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">Disco</div>
                    <div class="card-body">
                        <canvas id="diskChart"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">Red</div>
                    <div class="card-body">
                        <canvas id="networkChart"></canvas>
                        <p class="mt-3"><strong>Datos Recibidos (RX):</strong> <span id="rxData">Cargando...</span></p>
                        <p><strong>Datos Enviados (TX):</strong> <span id="txData">Cargando...</span></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Datos adicionales -->
        <div class="row g-4 mt-4">
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-header bg-dark text-white">Procesos Principales</div>
                    <div class="card-body">
                        <table class="table table-striped align-middle">
                            <thead>
                                <tr>
                                    <th>PID</th>
                                    <th>Comando</th>
                                    <th>CPU (%)</th>
                                    <th>Memoria (%)</th>
                                </tr>
                            </thead>
                            <tbody id="processTable">
                                <tr><td colspan="4">Cargando...</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-header bg-dark text-white">Particiones de Almacenamiento</div>
                    <div class="card-body">
                        <ul id="partitionList" class="list-group">
                            <li class="list-group-item">Cargando particiones...</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Servicios y certificados SSL -->
        <div class="row g-4 mt-4">
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-header bg-dark text-white">Servicios del Sistema</div>
                    <div class="card-body">
                        <ul id="serviceList" class="list-group">
                            <li class="list-group-item">Cargando servicios...</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-header bg-dark text-white">Certificados SSL</div>
                    <div class="card-body">
                        <ul id="sslList" class="list-group">
                            <li class="list-group-item">Cargando certificados...</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Logs -->
        <div class="row g-4 mt-4">
            <div class="col-md-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-dark text-white">Logs del Sistema</div>
                    <div class="card-body">
                        <pre id="systemLogs" class="bg-light p-3 rounded overflow-auto" style="max-height: 300px;">Cargando logs...</pre>
                    </div>
                </div>
            </div>
        </div>
            </div> <!-- cierre live tab -->
            <div class="tab-pane fade" id="history" role="tabpanel">
                <ul class="nav nav-pills mb-3" id="historyRange" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="day-tab" data-bs-toggle="pill" data-bs-target="#day" type="button" role="tab">Día</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="week-tab" data-bs-toggle="pill" data-bs-target="#week" type="button" role="tab">Semana</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="month-tab" data-bs-toggle="pill" data-bs-target="#month" type="button" role="tab">Mes</button>
                    </li>
                </ul>
                <div class="tab-content" id="historyRangeContent">
                    <div class="tab-pane fade show active" id="day" role="tabpanel">
                        <canvas id="historyDay"></canvas>
                    </div>
                    <div class="tab-pane fade" id="week" role="tabpanel">
                        <canvas id="historyWeek"></canvas>
                    </div>
                    <div class="tab-pane fade" id="month" role="tabpanel">
                        <canvas id="historyMonth"></canvas>
                    </div>
                </div>
            </div> <!-- cierre history tab -->
        </div> <!-- cierre tab-content -->
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

