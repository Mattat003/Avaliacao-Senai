<?php
require_once 'conexao.php';

$busca = $_GET['busca'] ?? '';
$produtos = [];

try {
    if (!empty($busca)) {
        if (ctype_digit($busca)) {
            // Se for número, buscar apenas por ID
            $stmt = $pdo->prepare("SELECT * FROM produto WHERE id_produto = :id");
            $stmt->bindValue(':id', (int)$busca, PDO::PARAM_INT);
        } else {
            // Se for texto, buscar por nome
            $stmt = $pdo->prepare("SELECT * FROM produto WHERE nome_prod LIKE :nome");
            $stmt->bindValue(':nome', '%' . $busca . '%');
        }
        $stmt->execute();
    } else {
        //input vazio = mostrar todos
        $stmt = $pdo->query("SELECT * FROM produto");
    }

    $produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Erro ao buscar produtos: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Buscar Produto</title>
    <link rel="stylesheet" href="estilo.css">
</head>
<body>

    <h1>Buscar Produto</h1>
    <a class="btn-voltar" href="principal.php">Voltar</a>
    <form method="GET" action="buscar_produto.php">
        <input type="text" name="busca" placeholder="Digite o ID ou nome do produto..." value="<?= htmlspecialchars($busca) ?>">
        <input type="submit" value="Buscar">
    </form>

    <?php if (count($produtos) > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Descrição</th>
                    <th>Quantidade</th>
                    <th>Valor Unitário</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($produtos as $produto): ?>
                    <tr>
                        <td><?= htmlspecialchars($produto['id_produto']) ?></td>
                        <td><?= htmlspecialchars($produto['nome_prod']) ?></td>
                        <td><?= htmlspecialchars($produto['descricao']) ?></td>
                        <td><?= htmlspecialchars($produto['qtde']) ?></td>
                        <td>R$ <?= number_format($produto['valor_unit'], 2, ',', '.') ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p class="nenhum">Nenhum produto encontrado.</p>
    <?php endif; ?>
</body>
</html>
