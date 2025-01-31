<?php
function obtenerProcesosPrincipales() {
    $output = shell_exec('ps -eo pid,comm,%cpu,%mem --sort=-%cpu | head -n 10');
    $lineas = explode("\n", trim($output));
    array_shift($lineas);  // Elimina la cabecera
    $procesos = [];

    foreach ($lineas as $linea) {
        // Usa una expresión regular más robusta para dividir la línea correctamente
        preg_match('/^(\d+)\s+(\S+)\s+([\d\.]+)\s+([\d\.]+)$/', trim($linea), $matches);
        
        if (count($matches) === 5) {
            $procesos[] = [
                'pid' => $matches[1],
                'comando' => $matches[2],
                'cpu' => $matches[3],
                'mem' => $matches[4]
            ];
        }
    }

    return $procesos;
}
?>

