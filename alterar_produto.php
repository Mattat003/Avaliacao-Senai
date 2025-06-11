<?php
session_start();
require 'conexao.php';

// Verifica se o produto tem permissão de ADM
if ($_SESSION['perfil'] != 1) {
    echo "<script>alert('Acesso negado!'); window.location.href='principal.php';</script>";
    exit();
}

// Inicializa variáveis
$produto = null;

// Se o formulário for enviado, busca o produto pelo ID ou nome
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!empty($_POST['busca_produto'])) {
        $busca = trim($_POST['busca_produto']);

        // Verifica se a busca é um número (ID) ou um nome
        if (is_numeric($busca)) {
            $sql = "SELECT * FROM produto WHERE id_produto = :busca";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':busca', $busca, PDO::PARAM_INT);
        } else {
            $sql = "SELECT * FROM produto WHERE nome_prod LIKE :busca_nome";
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':busca_nome', "%$busca%", PDO::PARAM_STR);
        }

        $stmt->execute();
        $produto = $stmt->fetch(PDO::FETCH_ASSOC);

        // Se o produto não for encontrado, exibe um alerta
        if (!$produto) {
            echo "<script>alert('Produto não encontrado!');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Alterar Usuário</title>
    <link rel="stylesheet" href="styles.css">
    
    <!-- Certifique-se de que o JavaScript está sendo carregado corretamente -->
    <script src="scripts.js"></script>
</head>
<body>
    <h2>Alterar Usuário</h2>

    <!-- Formulário para buscar produto pelo ID ou Nome -->
    <form action="alterar_produto.php" method="POST">
        <label for="busca_produto">Digite o ID ou Nome do produto:</label>
        <input type="text" id="busca_produto" name="busca_produto" required onkeyup="buscarSugestoes()">
        
        <!-- Div para exibir sugestões de produtos -->
        <div id="sugestoes"></div>
        
        <button type="submit">Buscar</button>
    </form>

    <?php if ($produto): ?>
        <!-- Formulário para alterar produto -->
        <form action="processa_alteracao_produto.php" method="POST">
            <input type="hidden" name="id_produto" value="<?= htmlspecialchars($produto['id_produto']) ?>">

            <label for="nome_prod">Nome:</label>
            <input type="text" id="nome_prod" name="nome_prod" value="<?= htmlspecialchars($produto['nome_prod']) ?>" required>

            <label for="descricao">Descrição:</label>
            <input type="text" id="descricao" name="descricao" value="<?= htmlspecialchars($produto['descricao']) ?>" required>

            <label for="qtde">Quantidade em Estoque:</label>
            <input type="text" id="qtde" name="qtde" value="<?= htmlspecialchars($produto['qtde']) ?>" required>

            <label for="qtde">Preço:</label>
            <input type="text" id="qtde" name="qtde" value="<?= htmlspecialchars($produto['valor_unit']) ?>" required>

            <button type="submit">Alterar</button>
            <button type="reset">Cancelar</button>
        </form>
    <?php endif; ?>

    <a href="principal.php">Voltar</a>
</body>
</html>