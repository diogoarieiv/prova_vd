<?php
session_start();
include('conexao.php');

// Verificar se o cliente está autenticado
if (!isset($_SESSION['cliente_id'])) {
    header('Location: login.php');
    exit();
}

// Verificar se há itens no carrinho
$carrinho = isset($_COOKIE['carrinho']) ? json_decode($_COOKIE['carrinho'], true) : [];

// Se o carrinho estiver vazio, exibir uma mensagem
if (empty($carrinho)) {
    echo "<p>Seu carrinho está vazio!</p>";
    exit();
}

// Calcular o total do carrinho
$total = 0;
foreach ($carrinho as $item) {
    $stmt = $pdo->prepare("SELECT * FROM produtos WHERE id = ?");
    $stmt->execute([$item['produto_id']]);
    $produto = $stmt->fetch(PDO::FETCH_ASSOC);
    $total += $produto['preco'] * $item['quantidade'];
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Carrinho de Compras</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <!-- Botão de Voltar para Produtos -->
    <div class="header-buttons">
        <a href="produtos.php" class="header-button">Voltar para Produtos</a>
        <a href="limpar_carrinho.php" class="header-button">Limpar Carrinho</a>
    </div>

    <h2>Carrinho de Compras</h2>

    <!-- Exibir os itens do carrinho -->
    <?php foreach ($carrinho as $item): ?>
        <?php
            $stmt = $pdo->prepare("SELECT * FROM produtos WHERE id = ?");
            $stmt->execute([$item['produto_id']]);
            $produto = $stmt->fetch(PDO::FETCH_ASSOC);
        ?>
        <div class="carrinho-item">
            <p><strong><?php echo htmlspecialchars($produto['nome']); ?></strong></p>
            <p>Quantidade: <?php echo $item['quantidade']; ?></p>
            <p>Preço Unitário: R$ <?php echo number_format($produto['preco'], 2, ',', '.'); ?></p>
            <p>Total: R$ <?php echo number_format($produto['preco'] * $item['quantidade'], 2, ',', '.'); ?></p>
            <a href="remover_item.php?produto_id=<?php echo $produto['id']; ?>" class="remove-button">Remover</a>
        </div>
    <?php endforeach; ?>

    <div class="carrinho-total">
        <h3>Total: R$ <?php echo number_format($total, 2, ',', '.'); ?></h3>
    </div>

    <!-- Formulário para Finalizar Compra -->
    <form method="POST" action="finalizar_compra.php">
        <!-- Passar os itens do carrinho para o formulário -->
        <?php foreach ($carrinho as $item): ?>
            <input type="hidden" name="produto_id[]" value="<?php echo $item['produto_id']; ?>">
            <input type="hidden" name="quantidade[]" value="<?php echo $item['quantidade']; ?>">
            <input type="hidden" name="preco[]" value="<?php echo $produto['preco']; ?>">
        <?php endforeach; ?>
        <button type="submit">Finalizar Compra</button>
    </form>
</body>
</html>
