<?php
function obtenerUsoRAM() {
    // Ejecutar los comandos para obtener la información de memoria
    $free = shell_exec('grep MemFree /proc/meminfo | awk \'{print $2}\'');
    $buffers = shell_exec('grep Buffers /proc/meminfo | awk \'{print $2}\'');
    $cached = shell_exec('grep Cached /proc/meminfo | awk \'{print $2}\'');

    $memTotal = shell_exec('grep MemTotal /proc/meminfo | awk \'{print $2}\'');

    // Convertir resultados a enteros para realizar cálculos
    $free = intval($free);
    $buffers = intval($buffers);
    $cached = intval($cached);
    $memTotal = intval($memTotal);

    // Calcular memoria usada y libre
    $memDisponible = $free + $buffers + $cached;
    $memUsado = $memTotal - $memDisponible;

    // Devolver los datos formateados
    return [
        'usado' => round($memUsado / 1024),  // Convertir a MB
        'libre' => round($memDisponible / 1024)  // Convertir a MB
    ];
}
?>
