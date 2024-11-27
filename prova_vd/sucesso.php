<?php
session_start();
include('conexao.php');

// Verificar se o cliente está autenticado
if (!isset($_SESSION['cliente_id'])) {
    header('Location: login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Compra Concluída</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <div class="sucesso-message">
            <h2>Compra Finalizada com Sucesso!</h2>
            <p>Obrigado pela sua compra. Seu pedido foi registrado com sucesso. Você será redirecionado para a página principal.</p>
            <a href="produtos.php" class="btn-primary">Voltar para Produtos</a>
        </div>
    </div>
</body>
</html>