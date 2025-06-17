--Matheus Felipe de Moura Leal--

<?php
    session_start();
    require_once 'conexao.php';

    //VERIFICA SE O USUARIO TEM PERMISSÃO
    //supondo que o perfil 1 seja o ADM
    if($_SESSION['perfil']!= 1){
        echo "<script>alert('acesso negado!');window.location.href=principal.php</script>";
    }

    if($_SERVER["REQUEST_METHOD"]=="POST"){
        $nome_prod = $_POST['nome_prod'];
        $descricao = $_POST['descricao'];
        $qtde = &$_POST['qtde'];
        $valor_unit = &$_POST['valor_unit'];

        $sql = "INSERT INTO produto(nome_prod, descricao, qtde, valor_unit) 
        VALUES(:nome_prod, :descricao, :qtde, :valor_unit)";
        $stmt = $pdo->prepare($sql);
        $stmt -> bindParam(':nome_prod', $nome_prod);
        $stmt -> bindParam(':descricao', $descricao);
        $stmt -> bindParam(':qtde', $qtde);
        $stmt -> bindParam(':valor_unit', $valor_unit);

        if($stmt->execute()){
            echo "<script>alert('Produto cadastrado com sucesso!');</script>";
        }else{
            echo "<script>alert('Erro ao cadastrar produto!');</script>";
        }
    }
?>
<!DOCTYPE html>
<html lang="pt-BR">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Cadastrar Produto</title>
        <link rel="stylesheet" href="estilo.css">
        <script src="ValidaCampos.js"></script>
    </head>
    <body>
        <h2>Cadastrar Produto</h2>
        <form action="cadastro_produto.php" method="post">
            <label for="nome_prod">Nome do Produto:</label>
            <input type="text" id="nome_prod" name="nome_prod" required>

            <label for="descricao">Descrição:</label>
            <input type="text" id="descricao" name="descricao" required>

            <label for="qtde">Quantidade:</label>
            <input type="text" id="qtde" name="qtde" required>

            <label for="valor_unit">Valor Unitário:</label>
            <input type="text" id="valor_unit" name="valor_unit" required>

            <button type="submit">Salvar</button>
            <button type="reset">Cancelar</button>
        </form>
        <a class="btn-voltar" href="principal.php">Voltar</a>
    </body>
</html>