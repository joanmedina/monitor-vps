<?php
function obtenerUsoSWAP() {
    $output = shell_exec('free -m | grep Swap');
    $datos = preg_split('/\s+/', trim($output));
    return [
        'total' => $datos[1] ?? 0,
        'usado' => $datos[2] ?? 0,
        'libre' => $datos[3] ?? 0
    ];
}
?>
