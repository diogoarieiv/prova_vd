<?php
session_start();
include('conexao.php');

// Verificar se o cliente está autenticado
if (!isset($_SESSION['cliente_id'])) {
    header('Location: login.php');
    exit();
}

// Obter o ID do produto a ser removido
$produto_id = $_GET['produto_id'];

// Verificar se há itens no carrinho
$carrinho = isset($_COOKIE['carrinho']) ? json_decode($_COOKIE['carrinho'], true) : [];

// Remover o item do carrinho
foreach ($carrinho as $index => $item) {
    if ($item['produto_id'] == $produto_id) {
        unset($carrinho[$index]);
        break;
    }
}

// Atualizar o cookie do carrinho
setcookie('carrinho', json_encode($carrinho), time() + (86400 * 30), '/');

// Redirecionar de volta para a página do carrinho
header('Location: carrinho.php');
exit();
?>
