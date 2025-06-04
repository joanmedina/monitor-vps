<?php
require_once 'metrics/cpu.php';
require_once 'metrics/memory.php';
require_once 'metrics/disk.php';
require_once 'metrics/network.php';

// Ruta a la base de datos SQLite
$dbPath = __DIR__ . '/data/metrics.sqlite';

try {
    $db = new PDO('sqlite:' . $dbPath);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Crear tabla si no existe
    $db->exec("CREATE TABLE IF NOT EXISTS metrics (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        timestamp INTEGER,
        cpu REAL,
        ram_used INTEGER,
        ram_free INTEGER,
        disk_used REAL,
        disk_free REAL,
        rx REAL,
        tx REAL
    )");

    // Obtener métricas
    $cpu = obtenerUsoCPU();
    $ram = obtenerUsoRAM();
    $disk = obtenerUsoDisco();
    $net = obtenerEstadisticasRed();

    $rx = is_string($net['rx']) ? floatval(str_replace(' MB', '', $net['rx'])) : 0;
    $tx = is_string($net['tx']) ? floatval(str_replace(' MB', '', $net['tx'])) : 0;

    // Insertar datos
    $stmt = $db->prepare('INSERT INTO metrics (timestamp, cpu, ram_used, ram_free, disk_used, disk_free, rx, tx)
                          VALUES (:timestamp, :cpu, :ram_used, :ram_free, :disk_used, :disk_free, :rx, :tx)');
    $stmt->execute([
        ':timestamp' => time(),
        ':cpu' => $cpu,
        ':ram_used' => $ram['usado'],
        ':ram_free' => $ram['libre'],
        ':disk_used' => $disk['usado'],
        ':disk_free' => $disk['libre'],
        ':rx' => $rx,
        ':tx' => $tx
    ]);

} catch (Exception $e) {
    error_log('Error al guardar métricas: ' . $e->getMessage());
    exit(1);
}
?>
