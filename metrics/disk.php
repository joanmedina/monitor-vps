<?php
function obtenerUsoDisco() {
    // Ejecutar el comando para obtener la información del disco
    $df = shell_exec('df -h / | awk \'{print $3 "," $4}\' | tail -n 1');

    // Verificar si el comando devolvió datos
    if (!empty($df)) {
        list($usado, $libre) = explode(',', trim($df));

        // Eliminar unidades (G, M, etc.) y convertir a número
        $usado = floatval(str_replace(['G', 'M'], '', $usado));
        $libre = floatval(str_replace(['G', 'M'], '', $libre));

        return [
            'usado' => $usado,
            'libre' => $libre
        ];
    }

    // Si no se obtienen datos, devolver valores por defecto
    return [
        'usado' => 0,
        'libre' => 0
    ];
}
?>

