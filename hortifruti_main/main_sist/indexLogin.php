<?php
session_start();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>

<h2>Login</h2>
<form action="processar_login.php" method="POST">
    <label for="username">Usuário:</label>
    <input type="text" id="username" name="username" required>
    <br><br>
    
    <label for="password">Senha:</label>
    <input type="password" id="password" name="password" required>
    <br><br>
    
    <button type="submit">Entrar</button>
</form>

<?php
// Exibir erros caso existam
if (isset($_SESSION['error'])) {
    echo "<p style='color: red;'>" . $_SESSION['error'] . "</p>";
    unset($_SESSION['error']);
}
?>

</body>
</html>
