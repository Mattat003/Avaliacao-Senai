<?php
    session_start();
    require_once "conexao.php";

    $produtos = []; //INICIALIZA A VARIAVEL PARA EVITAR ERROS

    //SE O FORMULARIO FOR ENVIADO, BUSCA O produto PELO ID OU NOME

    if($_SERVER["REQUEST_METHOD"]=="post"){
        $busca = trim($_POST['busca']);

        //VERIFICA SE A BUSCA É UM NUMERO(ID) OU UM NOME
        if(is_numeric($busca)){
            $sql = "SELECT * FROM produto WHERE id_produto = :busca ORDER BY nome_prod ASC";
            $stmt = $pdo->prepare($sql);
            $stmt -> bindParam(':busca', $busca, PDO::PARAM_INT);
        }else{
            $sql = "SELECT * FROM produto WHERE nome_prod LIKE :busca_nome ORDER BY nome_prod ASC";
            $stmt = $pdo->prepare($sql);
            $stmt -> bindValue(':busca_nome', "%$busca%", PDO::PARAM_STR);
        }
    }else{
        $sql = "SELECT * FROM produto ORDER BY nome_prod ASC";
        $stmt = $pdo->prepare($sql);
    }
    $stmt -> execute();
    $produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-BR">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Buscar Produto</title>
        <link rel="stylesheet" href="styles.css">
    </head>
    <body>
        <h2>Lista de Produtos</h2>
        <!--FORMULARIO PARA BUSCAR PRODUTOS-->
        <form action="buscar_produto.php" method="post">
            <label for="busca">Digite o ID ou NOME(opcional):</label>

            <input type="text" id="busca" name="busca">
            <button type="submit">Buscar</button>
        </form>

        <?php if(!empty($produtos)): ?>
            <table border="1">
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Descrição</th>
                    <th>Quantidade em Estoque</th>
                    <th>Preço</th>
                    <th>Ações</th>
                </tr>
                <?php foreach($produtos as $produto): ?>
                <tr>
                    <td><?= htmlspecialchars($produto['id_produto']) ?></td>
                    <td><?= htmlspecialchars($produto['nome_prod']) ?></td>
                    <td><?= htmlspecialchars($produto['descricao']) ?></td>
                    <td><?= htmlspecialchars($produto['qtde']) ?></td>
                    <td><?= htmlspecialchars($produto['valor_unit']) ?></td>

                    <td>
                        <a href="alterar_produto.php?id=<?= htmlspecialchars($produto['id_produto']) ?>">Alterar</a>

                        <a href="excluir_produto.php?id=<?= htmlspecialchars($produto['id_produto']) ?>" 
                        onclick="return confirm('Tem certeza que deseja excluir este produto?')">Excluir</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </table>
        <?php else: ?>
            <p>Nenhum produto encontrado.</p>
        <?php endif; ?>
        
        <a href="principal.php">Voltar</a>    
    </body>
</html>