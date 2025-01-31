<?php
function obtenerTemperaturaCPU() {
    $output = shell_exec('sensors | grep "Package id 0:"');
    if ($output) {
        preg_match('/\+([0-9]+\.[0-9]+)/', $output, $matches);
        return $matches[1] . ' °C';
    }
    return 'No disponible';
}
?>
