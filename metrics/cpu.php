<?php
function obtenerUsoCPU() {
    $carga = sys_getloadavg();
    return round($carga[0] * 100, 2);
}
?>