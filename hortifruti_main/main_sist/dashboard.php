<?php
session_start();
// Verificar se o usuário está logado
if (!isset($_SESSION['username'])) {
    header("Location: login.php"); // Redireciona para a tela de login se não estiver logado
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema Mercantil Lima - Dashboard</title>
    <link rel="stylesheet" href="/dashboard.css"> <!-- Se você estiver utilizando CSS externo -->
</head>
<body>
    <header>
        <h1>Bem-vindo ao Sistema Mercantil Lima, <?php echo $_SESSION['username']; ?>!</h1>
        <nav>
            <ul>
                <li><a href="registro_produto.php">Registrar Produto</a></li>
                <li><a href="relatorios.php">Relatórios</a></li>
                <li><a href="estoque.php">Gerenciar Estoque</a></li>
                <li><a href="comprar_produto.php">Compras</a></li>
                <li><a href="vender_produto.php">Vendas</a></li>
                <li><a href="perdas.php">Perdas</a></li>
                <li><a href="login.php">Sair</a></li>
            </ul>
        </nav>
    </header>

    <section id="dashboard">
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
    </section>

</body>
</html>
