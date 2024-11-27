<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Cliente</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="form-container">
        <h1>Cadastro</h1>
        <form action="cadastro_finalizar.php" method="POST" enctype="multipart/form-data">
            <label for="nome">Nome:</label>
            <input type="text" id="nome" name="nome" placeholder="Digite seu nome completo" required>

            <label for="email">E-mail:</label>
            <input type="email" id="email" name="email" placeholder="Digite um e-mail válido" required>

            <label for="senha">Senha:</label>
            <input type=password id="senha" name="senha" placeholder="Crie uma senha" required>

            <label for="foto">Foto de Perfil (.jpg):</label>
            <input type="file" id="foto" name="foto" accept=".jpg, .jpeg, .png, .gif" required>
            <br></br>
            <br></br>

            <label for="comprovante">Comprovante de Inscrição na Ordem dos Músicos:</label>
            <input type="file" id="comprovante" name="comprovante" accept=".pdf" required>
            <br></br>
            <br></br>

            <button type="submit">Cadastrar</button>
        </form>
    </div>
</body>
</html>