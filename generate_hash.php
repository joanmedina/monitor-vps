<?php
$password = $argv[1] ?? null;
if ($password === null) {
    fwrite(STDOUT, "Introduce la contraseña en texto plano: ");
    $password = trim(fgets(STDIN));
}
$hash = password_hash($password, PASSWORD_DEFAULT);

fwrite(STDOUT, "Hash generado: $hash\n");
