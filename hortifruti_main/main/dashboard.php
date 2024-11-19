<?php
session_start();
// Verificar se o usuário está logado
if (!isset($_SESSION['username'])) {
    header("Location: indexLogin.php"); // Redireciona para a tela de login se não estiver logado
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="..\SRC\CSS\dash.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>Dashboard</title>
</head>
<body>
    <nav id="sidebar">
        <div id="sidebar_content">
            <div id="user">
                <i class="fa-solid fa-user"></i>
                <p id="user_infos">
                    <span class="item-description">
                        <?php echo $_SESSION['username']; ?>
                    </span>
                </p>
            </div>
            <ul id="side_items">
                <li class="side-item active">
                    <a href="dashboard.php" >
                        <i class="fa-solid fa-box"></i>
                        <span class="item-description">Dashboard</span>
                    </a>
                </li>
                <li class="side-item">
                    <a href="adicionar_produto.php" >
                        <i class="fas fa-plus-circle"></i>
                        <span class="item-description">Incluir Quantidade</span>
                    </a>
                </li>
                <li class="side-item">
                    <a href="buscar_relatorio.php" data-file="relatorio.html">
                        <i class="fa-solid fa-chart-line"></i>
                        <span class="item-description">Relatórios</span>
                    </a>
                </li>
                <li class="side-item">
                    <a href="buscar_estoque.php" >
                        <i class="fas fa-cogs"></i>
                        <span class="item-description">Estoque</span>
                    </a>
                </li>
                <li class="side-item">
                    <a href="registrar_compra.php" >
                        <i class="fas fa-shopping-cart"></i>
                        <span class="item-description">Compras</span>
                    </a>
                </li>
                <li class="side-item">
                    <a href="vender_produto.php" >
                        <i class="fas fa-dollar-sign"></i>
                        <span class="item-description">Vendas</span>
                    </a>
                </li>
                <li class="side-item">
                    <a href="registrar_perda.php" >
                        <i class="fas fa-ban"></i>
                        <span class="item-description">Perdas</span>
                    </a>
                </li>
            </ul>
            <button id="open_btn">
                <i id="open_btn_icon" class="fa-solid fa-chevron-right"></i>
            </button>
        </div>
        <div id="logout">
            <a href="indexLogin.php" id="logout_btn" style="text-decoration: none;">
                <i class="fa-solid fa-right-from-bracket"></i>
                <span class="item-description">Sair</span>
            </a>
        </div>
    </nav>
    <main id="main-content">
        <h2>Bem-vindo ao Sistema!</h2>
        <p>Selecione uma opção no menu ao lado para começar.</p>
    </main>
    <script src="..\SRC\JS\dashboard.js"></script>
</body>
</html>
