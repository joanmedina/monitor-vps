<?php
function obtenerUsoCPU() {
    $carga = sys_getloadavg();
    $nucleos = intval(trim(shell_exec('nproc')));
    if ($nucleos < 1) {
        $nucleos = 1;
    }
    $porcentaje = ($carga[0] / $nucleos) * 100;
    return round($porcentaje, 2);
}
?>
