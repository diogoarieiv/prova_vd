<?php
session_start();
include('conexao.php');

// Verifica se o cliente está logado
if (!isset($_SESSION['cliente_id'])) {
    header('Location: login.php');
    exit();
}

// Exibindo os produtos
$stmt = $pdo->query("SELECT * FROM produtos");
$produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Verifica se o produto foi adicionado via POST (via AJAX)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['produto_id'])) {
    $produto_id = $_POST['produto_id'];
    $quantidade = $_POST['quantidade'];

    // Verificar se o carrinho já existe no cookie
    $carrinho = isset($_COOKIE['carrinho']) ? json_decode($_COOKIE['carrinho'], true) : [];

    // Adicionar ou atualizar o item no carrinho
    $item_encontrado = false;
    foreach ($carrinho as &$item) {
        if ($item['produto_id'] == $produto_id) {
            $item['quantidade'] += $quantidade; // Incrementa a quantidade
            $item_encontrado = true;
            break;
        }
    }

    if (!$item_encontrado) {
        // Adiciona o novo produto ao carrinho
        $carrinho[] = [
            'produto_id' => $produto_id,
            'quantidade' => $quantidade
        ];
    }

    // Atualizar o cookie com os novos itens do carrinho
    setcookie('carrinho', json_encode($carrinho), time() + 3600, "/"); // Cookie expira em 1 hora

    echo json_encode(['status' => 'success', 'message' => 'Produto adicionado ao carrinho!']);
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Produtos</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div class="header-buttons">
        <!-- Botões de navegação para Carrinho e Produtos -->
        <a href="carrinho.php" class="header-button">Carrinho</a>
        <a href="logout.php" class="header-button">Sair</a>
    </div>

    <h2>Produtos Disponíveis</h2>
    
    <div id="message"></div> <!-- Onde vamos mostrar a mensagem de sucesso/erro -->
    
    <?php foreach ($produtos as $produto): ?>
        <div class="produto">
            <h3><?php echo htmlspecialchars($produto['nome']); ?></h3>
            <p>Preço: R$ <?php echo number_format($produto['preco'], 2, ',', '.'); ?></p>
            <form class="add-to-cart-form" data-produto-id="<?php echo $produto['id']; ?>" method="POST" action="produtos.php">
                <input type="hidden" name="produto_id" value="<?php echo $produto['id']; ?>">
                <input type="number" name="quantidade" value="1" min="1" required>
                <button type="submit">Adicionar ao Carrinho</button>
            </form>
        </div>
    <?php endforeach; ?>
    
    <script>
        $(document).ready(function() {
            // Intercepta o envio do formulário para adicionar ao carrinho
            $('.add-to-cart-form').submit(function(e) {
                e.preventDefault(); // Impede o envio tradicional do formulário

                var form = $(this);
                var produto_id = form.find('input[name="produto_id"]').val();
                var quantidade = form.find('input[name="quantidade"]').val();

                // Envia a requisição AJAX para o servidor
                $.ajax({
                    url: 'produtos.php',
                    method: 'POST',
                    data: {
                        produto_id: produto_id,
                        quantidade: quantidade
                    },
                    success: function(response) {
                        var data = JSON.parse(response);
                        if (data.status === 'success') {
                            $('#message').html('<div class="alert success">' + data.message + '</div>');
                        } else {
                            $('#message').html('<div class="alert error">Erro ao adicionar ao carrinho!</div>');
                        }
                    },
                    error: function() {
                        $('#message').html('<div class="alert error">Erro ao adicionar ao carrinho!</div>');
                    }
                });
            });
        });
    </script>
</body>
</html>
