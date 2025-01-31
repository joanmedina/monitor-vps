<?php
function obtenerLoadAverage() {
    $load = sys_getloadavg();  // Obtiene los promedios de carga
    return implode(', ', $load);
}
?>
