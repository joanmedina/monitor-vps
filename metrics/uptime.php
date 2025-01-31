<?php
function obtenerUptime() {
    $uptime = shell_exec('uptime -p');
    return trim($uptime);
}
?>