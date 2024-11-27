<?php
session_start();
include('conexao.php');

// Verificar se o cliente está autenticado
if (!isset($_SESSION['cliente_id'])) {
    header('Location: login.php');
    exit();
}

// Verificar se os dados do formulário foram enviados
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $cliente_id = $_SESSION['cliente_id'];
    $produto_ids = $_POST['produto_id'];
    $quantidades = $_POST['quantidade'];
    $precos = $_POST['preco'];

    // Iniciar uma transação
    $pdo->beginTransaction();

    try {
        // Inserir cada item do carrinho na tabela de carrinho_compras
        $stmt = $pdo->prepare("INSERT INTO carrinho_compras (cliente_id, produto_id, quantidade) VALUES (?, ?, ?)");
        
        for ($i = 0; $i < count($produto_ids); $i++) {
            $stmt->execute([$cliente_id, $produto_ids[$i], $quantidades[$i]]);
        }

        // Commit da transação
        $pdo->commit();

        // Esvaziar o carrinho (remover o cookie)
        setcookie('carrinho', '', time() - 3600, '/');
        unset($_COOKIE['carrinho']);  // Também remover da variável superglobal

        // Redirecionar para a página de sucesso
        header('Location: sucesso.php');
        exit();
    } catch (Exception $e) {
        // Rollback da transação em caso de erro
        $pdo->rollBack();
        echo "Falha ao finalizar a compra: " . $e->getMessage();
    }
} else {
    header('Location: carrinho.php');
    exit();
}
?>
