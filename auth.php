<?php
require_once __DIR__ . '/vendor/autoload.php';

use Dotenv\Dotenv;

// Cargamos las variables de entorno desde el archivo .env
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

// Obtenemos el usuario y el hash de la contraseña permitidos
$usuario_permitido = $_ENV['USUARIO_PERMITIDO'] ?? '';
$password_hash = $_ENV['PASSWORD_PERMITIDA'] ?? '';

// Verificamos si se ha enviado la cabecera de autenticación HTTP
if (!isset($_SERVER['PHP_AUTH_USER']) || !isset($_SERVER['PHP_AUTH_PW']) ||
    $_SERVER['PHP_AUTH_USER'] !== $usuario_permitido ||
    !password_verify($_SERVER['PHP_AUTH_PW'], $password_hash)) {
    
    // Pedir autenticación si las credenciales son incorrectas
    header('WWW-Authenticate: Basic realm="Dashboard de Monitorización"');
    header('HTTP/1.0 401 Unauthorized');
    echo 'Acceso denegado. Debe autenticarse para continuar.';
    exit;
}
