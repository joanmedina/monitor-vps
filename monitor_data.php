<?php
require_once 'metrics/cpu.php';
require_once 'metrics/memory.php';
require_once 'metrics/disk.php';
require_once 'metrics/network.php';
require_once 'metrics/uptime.php';
require_once 'metrics/load_average.php';
require_once 'metrics/cpu_temperature.php';
require_once 'metrics/storage.php';
require_once 'metrics/processes.php';
require_once 'metrics/connections.php';
require_once 'metrics/ssl_certificates.php';
require_once 'metrics/swap.php';

// Configuración de la aplicación
$config = require __DIR__ . '/config.php';

// Obtener lista de servicios (Apache, MySQL, etc.)
function obtenerServicios() {
    global $config;
    $servicios = isset($config['services']) ? $config['services'] : [];
    $resultado = [];

    foreach ($servicios as $servicio) {
        $status = shell_exec("systemctl is-active $servicio");
        $resultado[] = [
            'name' => $servicio,
            'status' => trim($status) === 'active' ? 'Activo' : 'Inactivo'
        ];
    }

    return $resultado;
}

// Obtener logs del sistema
function obtenerLogs() {
    $logs = shell_exec('tail -n 20 /var/log/syslog');
    return $logs ?: 'No hay registros recientes.';
}

// Obtener número de procesos
function obtenerProcesosActivos() {
    return intval(shell_exec('ps -e | wc -l'));
}

// Obtener usuarios conectados
function obtenerUsuariosConectados() {
    $usuarios = shell_exec('who | wc -l');
    return intval($usuarios);
}

// Responder con los datos en formato JSON
echo json_encode([
    'cpu' => obtenerUsoCPU(),
    'ram' => obtenerUsoRAM(),
    'disco' => obtenerUsoDisco(),
    'red' => obtenerEstadisticasRed($_GET['iface'] ?? null),
    'uptime' => obtenerUptime(),
    'processes' => obtenerProcesosActivos(),
    'users' => obtenerUsuariosConectados(),
    'services' => obtenerServicios(),
    'logs' => obtenerLogs(),
    'load_average' => obtenerLoadAverage(),
    'cpu_temperature' => obtenerTemperaturaCPU(),
    'particiones' => obtenerParticiones(),
    'procesos_principales' => obtenerProcesosPrincipales(),
    'conexiones_abiertas' => obtenerConexionesAbiertas(),
    'ssl_certificates' => obtenerCertificadosSSL(['servermanager.es', 'vps1.servermanager.es']),
'swap' => obtenerUsoSWAP(),






]);
?>
