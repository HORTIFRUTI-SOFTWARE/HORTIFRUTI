<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Substitua "sua_senha_aqui" pela senha que você quer definir
$senha = "admin123";
$hash_senha = password_hash($senha, PASSWORD_DEFAULT);

echo "Hash da senha: " . $hash_senha;
?>
