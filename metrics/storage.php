<?php
function obtenerParticiones() {
    $particiones = [];
    $output = shell_exec('df -h');
    $lineas = explode("\n", trim($output));
    array_shift($lineas);  // Elimina la cabecera
    foreach ($lineas as $linea) {
        $datos = preg_split('/\s+/', $linea);
        $particiones[] = [
            'filesystem' => $datos[0],
            'montaje' => $datos[5],
            'usado' => $datos[2],
            'libre' => $datos[3],
            'total' => $datos[1]
        ];
    }
    return $particiones;
}
?>
