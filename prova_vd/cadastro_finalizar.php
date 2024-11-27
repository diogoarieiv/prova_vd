<?php
include("conexao.php");

$nome = $_POST['nome'];
$email = $_POST['email'];
$senha = $_POST['senha'];

// Criptografar a senha
$senhaHash = password_hash($senha, PASSWORD_DEFAULT);

// Configurações dos arquivos
$foto = $_FILES['foto'];
$comprovante = $_FILES['comprovante'];
$allowedTypesFoto = ['image/jpeg', 'image/png', 'image/gif', 'image/jpg'];
$allowedTypesComprovante = ['application/pdf'];
$fotoPath = "";
$comprovantePath = "";
$maxSize = 2 * 1024 * 1024; // 2MB

// Validação e upload da foto
if ($foto['error'] === 0) {
    if ($foto['size'] <= $maxSize && in_array($foto['type'], $allowedTypesFoto)) {
        $dimensions = getimagesize($foto['tmp_name']);
        if ($dimensions[0] <= 1024 && $dimensions[1] <= 1024) {
            $fotoPath = "uploads/" . uniqid() . "_" . basename($foto['name']);
            move_uploaded_file($foto['tmp_name'], $fotoPath);
        } else {
            die("Erro: As dimensões da foto devem ser no máximo 1024x1024 pixels.");
        }
    } else {
        die("Erro: A foto deve ser um arquivo JPEG, PNG ou GIF com tamanho máximo de 2MB.");
    }
} else {
    die("Erro no upload da foto.");
}

// Validação e upload do comprovante
if ($comprovante['error'] === 0) {
    if ($comprovante['size'] <= $maxSize && in_array($comprovante['type'], $allowedTypesComprovante)) {
        $comprovantePath = "uploads/" . uniqid() . "_" . basename($comprovante['name']);
        move_uploaded_file($comprovante['tmp_name'], $comprovantePath);
    } else {
        die("Erro: O comprovante deve ser um arquivo PDF com tamanho máximo de 2MB.");
    }
} else {
    die("Erro no upload do comprovante.");
}

// Preparar e executar a inserção no banco de dados com PDO
$sql = "INSERT INTO clientes (nome, email, senha, foto, pdf) VALUES (:nome, :email, :senha, :foto, :pdf)";
$stmt = $pdo->prepare($sql);

// Usar bindValue() para associar os parâmetros
$stmt->bindValue(':nome', $nome, PDO::PARAM_STR);
$stmt->bindValue(':email', $email, PDO::PARAM_STR);
$stmt->bindValue(':senha', $senhaHash, PDO::PARAM_STR);
$stmt->bindValue(':foto', $fotoPath, PDO::PARAM_STR);
$stmt->bindValue(':pdf', $comprovantePath, PDO::PARAM_STR);

// Executar o insert
if ($stmt->execute()) {
    echo '<script>
        alert("Cadastro realizado com sucesso");
        setTimeout(function() {
            window.location.href = "login.php";
        }, 500);
    </script>';
} else {
    die("Erro ao cadastrar: " . $stmt->errorInfo()[2]);
}

// Fechar a conexão
$pdo = null;
?>
