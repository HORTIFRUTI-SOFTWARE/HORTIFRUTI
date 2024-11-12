<?php
session_start();
require_once 'config.php'; // Incluindo o arquivo de configuração do banco de dados

// Conectar ao banco de dados
$conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

// Obter os dados do formulário
$username = $_POST['username'];
$password = $_POST['password'];

// Verificar se o usuário existe
$sql = "SELECT * FROM usuarios WHERE nome_usuario = ?"; // Alterado para nome_usuario
$stmt = $conn->prepare($sql);
$stmt->bind_param('s', $username);
$stmt->execute();
$result = $stmt->get_result();

// Verificar se encontrou o usuário
if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    
    // Verificar se a senha corresponde ao hash armazenado
    if (password_verify($password, $user['senha'])) {
        // Login bem-sucedido
        $_SESSION['username'] = $username;
        header('Location: dashboard.php'); // Redireciona para a página do sistema
        exit();
    } else {
        // Senha incorreta
        $_SESSION['error'] = "Senha incorreta!";
        header('Location: login.php');
        exit();
    }
} else {
    // Usuário não encontrado
    $_SESSION['error'] = "Usuário não encontrado!";
    header('Location: login.php');
    exit();
}

$conn->close();
?>
