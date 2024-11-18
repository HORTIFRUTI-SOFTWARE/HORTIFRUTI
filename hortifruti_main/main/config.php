<?php
// Defina suas credenciais de banco de dados aqui
$servername = "localhost"; // O endereço do servidor de banco de dados
$dbusername = "root"; // Seu usuário do banco de dados (geralmente 'root' para XAMPP)
$dbpassword = ""; // Senha do banco de dados (deixe vazio se for 'root' no XAMPP)
$dbname = "hortfrut"; // O nome do banco de dados que você criou

// Criando a conexão
$conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

// Verificando a conexão
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}
?>
