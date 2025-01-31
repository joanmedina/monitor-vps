<?php
// Definimos el usuario y contraseña permitidos
$usuario_permitido = 'admin';
$password_permitida = 'segura123';

// Verificamos si se ha enviado la cabecera de autenticación HTTP
if (!isset($_SERVER['PHP_AUTH_USER']) || !isset($_SERVER['PHP_AUTH_PW']) ||
    $_SERVER['PHP_AUTH_USER'] !== $usuario_permitido || $_SERVER['PHP_AUTH_PW'] !== $password_permitida) {
    
    // Pedir autenticación si las credenciales son incorrectas
    header('WWW-Authenticate: Basic realm="Dashboard de Monitorización"');
    header('HTTP/1.0 401 Unauthorized');
    echo 'Acceso denegado. Debe autenticarse para continuar.';
    exit;
}
