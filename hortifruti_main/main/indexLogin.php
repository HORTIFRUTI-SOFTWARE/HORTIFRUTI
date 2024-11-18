<?php
session_start();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="..\src\css\login.css">
</head>
<body>

<!-- Container para centralizar o conteúdo -->
<div class="login-container">
    <!-- Formulário de login -->
    <form class="form" action="processar_login.php" method="POST">
        <div class="form-title"><span>MERCANTIL</span></div>
        <div class="title-2"><span>LIMA</span></div>

        <!-- Container para o campo de usuário -->
        <div class="input-container">
            <input placeholder="Usuário" type="text" id="username" name="username" required class="input-mail" />
        </div>

        <!-- Seção de estrelas para o fundo -->
        <section class="bg-stars">
            <span class="star"></span>
            <span class="star"></span>
            <span class="star"></span>
            <span class="star"></span>
        </section>

        <!-- Container para o campo de senha -->
        <div class="input-container">
            <input placeholder="Senha" type="password" id="password" name="password" required class="input-pwd" />
        </div>

        <!-- Botão de envio -->
        <button class="submit" type="submit">
            <span class="sign-text">Entrar</span>
        </button>
    </form>

    <!-- Exibir erros -->
    <?php
    if (isset($_SESSION['error'])) {
        echo "<p style='color: red; text-align: center;'>" . $_SESSION['error'] . "</p>";
        unset($_SESSION['error']);
    }
    ?>
</div>

</body>
</html>
