<?php
function obtenerConexionesAbiertas() {
    $output = shell_exec('netstat -tunap | tail -n +3');
    $lineas = explode("\n", trim($output));
    $conexiones = [];
    foreach ($lineas as $linea) {
        $datos = preg_split('/\s+/', $linea);
        if (count($datos) >= 6) {
            $conexiones[] = [
                'protocolo' => $datos[0],
                'direccion_local' => $datos[3],
                'direccion_remota' => $datos[4],
                'estado' => $datos[5] ?? 'N/A'
            ];
        }
    }
    return $conexiones;
}
?>
