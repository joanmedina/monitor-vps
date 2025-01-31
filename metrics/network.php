<?php
function obtenerEstadisticasRed() {
    $netStats = shell_exec('cat /proc/net/dev | grep eth0');
    if (!$netStats) return ['rx' => 'No disponible', 'tx' => 'No disponible'];
    preg_match('/eth0:\s+(\d+)\s+\d+\s+\d+\s+\d+\s+\d+\s+\d+\s+\d+\s+\d+\s+(\d+)/', $netStats, $matches);
    return ['rx' => round($matches[1] / (1024 ** 2), 2) . " MB", 'tx' => round($matches[2] / (1024 ** 2), 2) . " MB"];
}
?>