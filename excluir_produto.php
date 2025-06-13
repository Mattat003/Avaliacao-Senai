<?php
session_start();
require 'conexao.php';

// Verifica se o usuário tem permissão de ADM
if ($_SESSION['perfil'] != 1 && $_SESSION['perfil'] != 3) {
    echo "<script>alert('Acesso negado!'); window.location.href='principal.php';</script>";
    exit();
}

// Inicializa variável para armazenar produtos
$produtos = [];

// Busca todos os produtos cadastrados em ordem alfabética
$sql = "SELECT * FROM produto ORDER BY nome_prod ASC";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Se um ID for passado via GET, exclui o produto
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id_produto = $_GET['id'];

    // Exclui o Produto do banco de dados
    $sql = "DELETE FROM produto WHERE id_produto = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id_produto, PDO::PARAM_INT);

    if ($stmt->execute()) {
        echo "<script>alert('Produto excluído com sucesso!'); window.location.href='excluir_produto.php';</script>";
    } else {
        echo "<script>alert('Erro ao excluir produto!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Excluir Produto</title>
    <link rel="stylesheet" href="estilo.css">    
</head>
<body>
    <h2>Excluir Produto</h2>
    <a class="btn-voltar" href="principal.php">Voltar</a>
    <?php if (!empty($produtos)): ?>
        <table border="1">
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Descrição</th>
                <th>Quantidade em Estoque</th>
                <th>Preço</th>
                <th>Ações</th>
            </tr>
            <?php foreach ($produtos as $produto): ?>
                <tr>
                    <td><?= htmlspecialchars($produto['id_produto']) ?></td>
                    <td><?= htmlspecialchars($produto['nome_prod']) ?></td>
                    <td><?= htmlspecialchars($produto['descricao']) ?></td>
                    <td><?= htmlspecialchars($produto['qtde']) ?></td>
                    <td><?= htmlspecialchars($produto['valor_unit']) ?></td>
                    <td>
                    <a class="btn-excluir" href="excluir_produto.php?id=<?= htmlspecialchars($produto['id_produto']) ?>" onclick="return confirm('Tem certeza que deseja excluir este produto?')">Excluir</a>

                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <p>Nenhum produto encontrado.</p>
    <?php endif; ?>
</body>
</html>