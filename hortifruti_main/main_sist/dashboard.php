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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="..\src\css\dashboard.css">
    <title>Sidebar</title>
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
                    <a href="#">
                        <i class="fa-solid fa-box"></i>
                        <span class="item-description">
                            Dashboard
                        </span>
                    </a>
                </li>

                <li class="side-item">
                    <a href="#">
                        <i class="fas fa-plus-circle"></i>
                        <span class="item-description">
                            Registrar Produto
                        </span>
                    </a>
                </li>
    
                <li class="side-item">
                    <a href="#">
                        <i class="fa-solid fa-chart-line"></i>
                        <span class="item-description">
                            Relatórios
                        </span>
                    </a>
                </li>
    
                <li class="side-item">
                    <a href="#">
                        <i class="fas fa-cogs"></i>
                        <span class="item-description">
                            Gerenciar Estoque
                        </span>
                    </a>
                </li>
    
                <li class="side-item">
                    <a href="#">
                        <i class="fas fa-shopping-cart"></i>
                        <span class="item-description">
                            Compras
                        </span>
                    </a>
                </li>
    
                <li class="side-item">
                    <a href="#">
                        <i class="fas fa-dollar-sign"></i>
                        <span class="item-description">
                            Vendas
                        </span>
                    </a>
                </li>
    
                <li class="side-item">
                    <a href="#">
                        <i class="fas fa-ban"></i>
                        <span class="item-description">
                            Perdas
                        </span>
                    </a>
                </li>
            </ul>
    
            <button id="open_btn">
                <i id="open_btn_icon" class="fa-solid fa-chevron-right"></i>
            </button>
        </div>

        <div id="logout">
            <button id="logout_btn">
                <i class="fa-solid fa-right-from-bracket"></i>
                <span class="item-description">
                    Logout
                </span>
            </button>
        </div>
    </nav>

    <main>
        <h2>Funções do Sistema</h2>
        <div class="cards">
            <div class="card">
                <h3>Registrar Produto</h3>
                <p>Adicione novos produtos ao estoque.</p>
                <a href="registro_produto.php">Ir para Registro</a>
            </div>

            <div class="card">
                <h3>Relatórios</h3>
                <p>Visualize os relatórios de vendas, compras, perdas e lucros.</p>
                <a href="relatorios.php">Ir para Relatórios</a>
            </div>

            <div class="card">
                <h3>Gerenciar Estoque</h3>
                <p>Controle o estoque de produtos disponíveis.</p>
                <a href="estoque.php">Ir para Estoque</a>
            </div>

            <div class="card">
                <h3>Compras</h3>
                <p>Gerencie as compras realizadas para o estoque.</p>
                <a href="compras.php">Ir para Compras</a>
            </div>

            <div class="card">
                <h3>Vendas</h3>
                <p>Registre e visualize as vendas realizadas.</p>
                <a href="vendas.php">Ir para Vendas</a>
            </div>

            <div class="card">
                <h3>Perdas</h3>
                <p>Gerencie as perdas de produtos no estoque.</p>
                <a href="perdas.php">Ir para Perdas</a>
            </div>
        </div>
    </main>
    <script src="..\src\js\dashboard.js"></script>
</body>
</html>
