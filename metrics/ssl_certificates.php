<?php
function obtenerCertificadosSSL($dominios) {
    $certificados = [];
    foreach ($dominios as $dominio) {
        $output = shell_exec("echo | openssl s_client -servername $dominio -connect $dominio:443 2>/dev/null | openssl x509 -noout -dates");
        if ($output) {
            preg_match('/notAfter=(.*)/', $output, $matches);
            $fecha_expiracion = $matches[1] ?? 'Desconocido';
            $certificados[] = [
                'dominio' => $dominio,
                'expiracion' => $fecha_expiracion
            ];
        } else {
            $certificados[] = [
                'dominio' => $dominio,
                'expiracion' => 'No disponible'
            ];
        }
    }
    return $certificados;
}
?>
