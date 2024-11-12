<?php
session_start();
include('config.php'); // Conexão com o banco de dados

// Verificar se o usuário está logado
if (!isset($_SESSION['username'])) {
    header("Location: indexLogin.php"); // Redireciona para login se não estiver logado
    exit();
}

// Definir o período para o filtro
$periodo_inicio = isset($_POST['inicio']) ? $_POST['inicio'] : null;
$periodo_fim = isset($_POST['fim']) ? $_POST['fim'] : null;

// Função para gerar relatório de compras
function gerarRelatorioCompras($conn, $inicio, $fim) {
    $sql = "SELECT p.nome, SUM(c.qntd_kg) AS total_kg, SUM(c.total) AS total_gasto
            FROM compras c
            JOIN produtos p ON c.id_produto = p.id
            WHERE 1";

    // Adicionar filtros de data, se fornecido
    if ($inicio && $fim) {
        $sql .= " AND c.data_compra BETWEEN '$inicio' AND '$fim'";
    }

    $sql .= " GROUP BY p.id";
    return $conn->query($sql);
}

// Função para gerar relatório de vendas
function gerarRelatorioVendas($conn, $inicio, $fim) {
    $sql = "SELECT p.nome, SUM(v.qntd_kg) AS total_kg, SUM(v.total) AS total_vendido
            FROM vendas v
            JOIN produtos p ON v.id_produto = p.id
            WHERE 1";

    // Adicionar filtros de data, se fornecido
    if ($inicio && $fim) {
        $sql .= " AND v.data_compra BETWEEN '$inicio' AND '$fim'";
    }

    $sql .= " GROUP BY p.id";
    return $conn->query($sql);
}

// Função para gerar relatório total de transações (compras + vendas)
function gerarRelatorioTotal($conn, $inicio, $fim) {
    $sql = "SELECT tipo, SUM(qntd_kg) AS total_kg, SUM(total) AS total_transacionado
            FROM (
                SELECT 'compra' AS tipo, qntd_kg, total FROM compras
                UNION ALL
                SELECT 'venda' AS tipo, qntd_kg, total FROM vendas
            ) AS transacoes
            WHERE 1";

    // Adicionar filtros de data, se fornecido
    if ($inicio && $fim) {
        $sql .= " AND data_compra BETWEEN '$inicio' AND '$fim'";
    }

    $sql .= " GROUP BY tipo";
    return $conn->query($sql);
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatórios de Compras e Vendas</title>
</head>
<body>
    <header>
        <h1>Relatórios de Compras e Vendas</h1>
        <nav>
            <a href="dashboard.php">• Voltar ao Ínicio</a>
        </nav>
    </header>

    <section id="formulario">
        <!-- Formulário de filtro de datas -->
        <form method="POST" action="">
            <label for="inicio">Data de Início:</label>
            <input type="date" name="inicio"><br>

            <label for="fim">Data de Fim:</label>
            <input type="date" name="fim"><br>

            <button type="submit">Gerar Relatório</button>
        </form>
    </section>

    <section id="relatorios">
        <h2>Relatório de Compras</h2>
        <?php
        $compras = gerarRelatorioCompras($conn, $periodo_inicio, $periodo_fim);
        if ($compras->num_rows > 0) {
            echo "<table><tr><th>Produto</th><th>Total (kg)</th><th>Total Gasto</th></tr>";
            while ($linha = $compras->fetch_assoc()) {
                echo "<tr><td>" . $linha['nome'] . "</td><td>" . $linha['total_kg'] . "</td><td>" . number_format($linha['total_gasto'], 2, ',', '.') . "</td></tr>";
            }
            echo "</table>";
        } else {
            echo "<p>Nenhuma compra registrada para este período.</p>";
        }
        ?>

        <h2>Relatório de Vendas</h2>
        <?php
        $vendas = gerarRelatorioVendas($conn, $periodo_inicio, $periodo_fim);
        if ($vendas->num_rows > 0) {
            echo "<table><tr><th>Produto</th><th>Total (kg)</th><th>Total Vendido</th></tr>";
            while ($linha = $vendas->fetch_assoc()) {
                echo "<tr><td>" . $linha['nome'] . "</td><td>" . $linha['total_kg'] . "</td><td>" . number_format($linha['total_vendido'], 2, ',', '.') . "</td></tr>";
            }
            echo "</table>";
        } else {
            echo "<p>Nenhuma venda registrada para este período.</p>";
        }
        ?>

        <h2>Relatório Total de Transações</h2>
        <?php
        $total = gerarRelatorioTotal($conn, $periodo_inicio, $periodo_fim);
        if ($total->num_rows > 0) {
            echo "<table><tr><th>Tipo</th><th>Total (kg)</th><th>Total Transacionado</th></tr>";
            while ($linha = $total->fetch_assoc()) {
                echo "<tr><td>" . ucfirst($linha['tipo']) . "</td><td>" . $linha['total_kg'] . "</td><td>" . number_format($linha['total_transacionado'], 2, ',', '.') . "</td></tr>";
            }
            echo "</table>";
        } else {
            echo "<p>Nenhuma transação registrada para este período.</p>";
        }
        ?>
    </section>
</body>
</html>
