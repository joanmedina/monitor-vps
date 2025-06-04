<?php
function obtenerInterfazPredeterminada() {
    $iface = trim(shell_exec("ip route 2>/dev/null | awk '/default/ {print \$5; exit}'"));
    if (!$iface) {
        $iface = trim(shell_exec("route -n 2>/dev/null | awk '/^0.0.0.0/ {print \$8; exit}'"));
    }
    return $iface ?: 'eth0';
}

function obtenerEstadisticasRed($interfaces = null) {
    if ($interfaces === null) {
        $interfaces = [obtenerInterfazPredeterminada()];
    } elseif (!is_array($interfaces)) {
        $interfaces = array_map('trim', explode(',', $interfaces));
    }

    $rxTotal = 0;
    $txTotal = 0;
    foreach ($interfaces as $iface) {
        $line = shell_exec("cat /proc/net/dev | grep {$iface}:");
        if ($line) {
            $parts = preg_split('/\s+/', trim(str_replace(':', ' ', $line)));
            if (isset($parts[1]) && isset($parts[9])) {
                $rxTotal += (int)$parts[1];
                $txTotal += (int)$parts[9];
            }
        }
    }

    if ($rxTotal === 0 && $txTotal === 0) {
        return ['rx' => 'No disponible', 'tx' => 'No disponible', 'interface' => implode(',', $interfaces)];
    }

    return [
        'rx' => round($rxTotal / (1024 ** 2), 2) . ' MB',
        'tx' => round($txTotal / (1024 ** 2), 2) . ' MB',
        'interface' => implode(',', $interfaces)
    ];
}
?>
