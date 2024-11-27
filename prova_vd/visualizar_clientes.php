<?php
session_start();
include('conexao.php');

// Buscar todos os clientes do banco de dados
$stmt = $pdo->query("SELECT * FROM clientes");
$clientes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Visualizar Clientes</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>Lista de Clientes</h1>
        </header>

        <?php if (count($clientes) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Email</th>
                        <th>Foto</th>
                        <th>Documento</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($clientes as $cliente): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($cliente['id']); ?></td>
                            <td><?php echo htmlspecialchars($cliente['nome']); ?></td>
                            <td><?php echo htmlspecialchars($cliente['email']); ?></td>
                            <td>
                                <!-- Verificar se a foto existe e exibir -->
                                <?php if (!empty($cliente['foto'])): ?>
                                    <img src="<?php echo htmlspecialchars($cliente['foto']); ?>" alt="Foto de Perfil" width="200">
                                <?php else: ?>
                                    <p>Foto não disponível.</p>
                                <?php endif; ?>
                            </td>
                            <td>
                                <p><strong>Ver comprovante</strong></p>
                                <!-- Verificar se o comprovante existe e exibir -->
                                <?php if (!empty($cliente['pdf'])): ?>
                                    <a href="<?php echo htmlspecialchars($cliente['pdf']); ?>" target="_blank">Visualizar Comprovante</a>
                                <?php else: ?>
                                    <p>Comprovante não disponível.</p>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Nenhum cliente encontrado.</p>
        <?php endif; ?>

        <footer>
            <p><a href="index.php">Voltar</a></p>
        </footer>
    </div>
</body>
</html>
