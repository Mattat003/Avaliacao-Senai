<?php
require_once 'conexao.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id_produto = $_POST['id_produto'] ?? null;
    $nome_prod = $_POST['nome_prod'] ?? '';
    $descricao = $_POST['descricao'] ?? '';
    $qtde = $_POST['qtde'] ?? 0;
    $valor_unit = $_POST['valor_unit'] ?? 0;

    if ($id_produto && $pdo) {
        try {
            $sql = "UPDATE produto 
                    SET nome_prod = :nome_prod, descricao = :descricao, qtde = :qtde, valor_unit = :valor_unit 
                    WHERE id_produto = :id_produto";

            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':nome_prod' => $nome_prod,
                ':descricao' => $descricao,
                ':qtde' => $qtde,
                ':valor_unit' => $valor_unit,
                ':id_produto' => $id_produto
            ]);

            // Buscar o produto atualizado para exibir
            $stmtSelect = $pdo->prepare("SELECT * FROM produto WHERE id_produto = :id");
            $stmtSelect->execute([':id' => $id_produto]);
            $produto = $stmtSelect->fetch(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            echo "Erro ao atualizar produto: " . $e->getMessage();
            exit;
        }
    } else {
        echo "ID do produto não informado ou conexão falhou.";
        exit;
    }
} else {
    echo "Requisição inválida.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Produto Alterado</title>
    <link rel="stylesheet" href="estilo.css">
</head>
<body>

    <h1>Produto alterado com sucesso!</h1>
    <p>Veja abaixo as informações atualizadas:</p>

    <table>
        <tr><th>ID</th><th>Nome</th><th>Descrição</th><th>Quantidade</th><th>Valor Unitário</th></tr>
        <tr>
            <td><?= htmlspecialchars($produto['id_produto']) ?></td>
            <td><?= htmlspecialchars($produto['nome_prod']) ?></td>
            <td><?= htmlspecialchars($produto['descricao']) ?></td>
            <td><?= htmlspecialchars($produto['qtde']) ?></td>
            <td>R$ <?= number_format($produto['valor_unit'], 2, ',', '.') ?></td>
        </tr>
    </table>

    <div class="botoes">
        <p>Deseja alterar outro produto?</p>
        <a href="alterar_produto.php" class="btn-voltar">Sim</a>
        <a href="principal.php" class="btn-nao">Não</a>
    </div>
    <a class="btn-voltar" href="principal.php">Voltar</a>
</body>
</html>
