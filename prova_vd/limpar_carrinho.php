<?php
// Limpar o carrinho (remover o cookie)
setcookie('carrinho', '', time() - 3600, '/');
unset($_COOKIE['carrinho']);  // Também remover da variável superglobal

// Redirecionar de volta para a página do carrinho
header('Location: carrinho.php');
exit();
?>
